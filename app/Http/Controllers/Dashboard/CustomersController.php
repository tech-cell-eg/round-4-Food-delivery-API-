<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNewCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\User;
use App\Traits\MediaHandler;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomersController extends Controller
{
    use MediaHandler;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = 10;
        $page = $request->get('page', 1);

        $totalCustomers = Customer::count();
        $maxPage = ceil($totalCustomers / $perPage);

        if ($page > $maxPage && $maxPage > 0) {
            return redirect()->route('admin.customers.index', ['page' => $maxPage]);
        }

        if ($page < 1) {
            return redirect()->route('admin.customers.index', ['page' => 1]);
        }

        $customers = Customer::orderBy("created_at", "desc")
            ->with(["user:id,name,email,phone,profile_image,bio,email_verified_at,created_at,updated_at"])
            ->paginate($perPage);

        $customersCount = $customers->total();

        $verifiedAccounts = User::whereNotNull("email_verified_at")->where("type", "customer")->count();

        $activeAccounts = Customer::where("status", "active")->count();

        return view("dashboard.pages.customers.index", get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("dashboard.pages.customers.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateNewCustomerRequest $request)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validated();

            $user = $this->createUserWithImage($validatedData, $request);

            $this->createCustomer($user->id, $validatedData["status"] ?? "active");

            DB::commit();

            return redirect()
                ->route('admin.customers.index')
                ->with('success', 'Customer created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the customer. Please try again.');
        }
    }

    protected function createUserWithImage(array $validatedData, Request $request): User
    {
        $userData = [
            "name" => $validatedData["name"],
            "email" => $validatedData["email"],
            "phone" => $validatedData["phone"],
            "bio" => $validatedData["bio"],
            "password" => Hash::make($validatedData["password"]),
            "email_verified_at" => !empty($validatedData["email_verified"]) ? now() : null,
        ];

        if ($request->hasFile("profile_image")) {
            $userData['profile_image'] = $this->storeImage($request->file('profile_image'), 'users_images');
        }

        return User::create($userData);
    }

    protected function createCustomer(int $userId, string $status = "active"): Customer
    {
        return Customer::create([
            "id" => $userId,
            "status" => $status,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = Customer::with(["user:id,name,email,phone,profile_image,bio,email_verified_at,created_at,updated_at"])
            ->findOrFail($id);

        return view('dashboard.pages.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, string $id)
    {
        DB::beginTransaction();

        try {

            $validatedData = $request->validated();

            $customer = Customer::findOrFail($id);

            $this->updateUserWithImage($customer, $validatedData, $request);

            $customer->status = $validatedData["status"] ?? "active";
            $customer->save();

            DB::commit();

            return redirect()
                ->route('admin.customers.index')
                ->with('success', 'Customer Updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the customer. Please try again.');
        }

    }

    protected function updateUserWithImage(Customer $customer, array $validatedData, Request $request): void
    {
        $userData = [
            "name" => $validatedData["name"],
            "email" => $validatedData["email"],
            "phone" => $validatedData["phone"],
            "bio" => $validatedData["bio"] ?? null,
            "email_verified_at" => ! empty($validatedData["email_verified"]) ? now() : null,
        ];

        if(! empty($validatedData["password"])) {
            $userData['password'] = Hash::make($validatedData["password"]);
        }

        if ($request->hasFile("profile_image")) {
            if ($customer->user && $customer->user->profile_image && Storage::disk('public')->exists($customer->user->profile_image)) {
                $this->deleteImage($customer->user->profile_image);
            }

            $userData['profile_image'] = $this->storeImage($request->file('profile_image'), 'users_images');
        }

        $customer->user->update($userData);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);

        if ($customer->user && $customer->user->profile_image && Storage::disk('public')->exists($customer->user->profile_image)) {
            $this->deleteImage($customer->user->profile_image);
        }

        if ($customer->user) {
            $customer->user->delete();
        }

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer Deleted successfully');
    }
}
