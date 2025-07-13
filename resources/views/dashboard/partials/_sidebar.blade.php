<!--sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('assets/images') }}/logo-icon.png" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Food Delivery</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>

        <li class="menu-label">Management</li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-user-check'></i>
                </div>
                <div class="menu-title">Admins</div>
            </a>
            <ul>
                <li> <a href="{{ route('admin.admins.index') }}"><i class='bx bx-radio-circle'></i>All Admins</a>
                </li>
                <li> <a href="{{ route('admin.admins.create') }}"><i class='bx bx-radio-circle'></i>Add New Admin</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-user'></i>
                </div>
                <div class="menu-title">Customers</div>
            </a>
            <ul>
                <li> <a href="{{ route('admin.customers.index')  }}"><i class='bx bx-radio-circle'></i>All Customers</a>
                </li>
                <li> <a href="{{ route('admin.customers.create') }}"><i class='bx bx-radio-circle'></i>Add New Customer</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-restaurant'></i>
                </div>
                <div class="menu-title">Chefs</div>
            </a>
            <ul>
                <li> <a href="{{ route('admin.chefs.index') }}"><i class='bx bx-radio-circle'></i>All Chefs</a>
                </li>
                <li> <a href="{{ route('admin.chefs.create') }}"><i class='bx bx-radio-circle'></i>Add New Chef</a>
                </li>
            </ul>
        </li>

        <li class="menu-label">Access Control</li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-shield'></i>
                </div>
                <div class="menu-title">Roles</div>
            </a>
            <ul>
                <li> <a href="{{ route('admin.roles.index') }}"><i class='bx bx-radio-circle'></i>All Roles</a>
                </li>
                <li> <a href="{{ route('admin.roles.create') }}"><i class='bx bx-radio-circle'></i>Add New Role</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-key'></i>
                </div>
                <div class="menu-title">Permissions</div>
            </a>
            <ul>
                <li> <a href="{{ route('admin.permissions.index') }}"><i class='bx bx-radio-circle'></i>All Permissions</a>
                </li>
                <li> <a href="{{ route('admin.permissions.create') }}"><i class='bx bx-radio-circle'></i>Add New Permission</a>
                </li>
            </ul>
        </li>

        <li class="menu-label">Food Management</li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-category'></i>
                </div>
                <div class="menu-title">Categories</div>
            </a>
            <ul>
                <li> <a href="{{ route('admin.categories.index') }}"><i class='bx bx-radio-circle'></i>All Categories</a>
                </li>
                <li> <a href="{{ route('admin.categories.create') }}"><i class='bx bx-radio-circle'></i>Add New Category</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-dish'></i>
                </div>
                <div class="menu-title">Dishes</div>
            </a>
            <ul>
                <li> <a href="{{ route('admin.dishes.index') }}"><i class='bx bx-radio-circle'></i>All Dishes</a>
                </li>
                <li> <a href="{{ route('admin.dishes.create') }}"><i class='bx bx-radio-circle'></i>Add New Dish</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-food-menu'></i></div>
                <div class="menu-title">Ingredient</div>
            </a>
            <ul>
                <li> <a href="{{ route('admin.ingredients.index') }}"><i class='bx bx-radio-circle'></i>All Ingredients</a></li>
                <li> <a href="{{ route('admin.ingredients.create') }}"><i class='bx bx-radio-circle'></i>Add New Ingredient</a></li>
            </ul>
        </li>



        <li class="menu-label">Orders & Sales</li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-cart'></i>
                </div>
                <div class="menu-title">Orders</div>
            </a>
            <ul>
                <li> <a href="{{ route('admin.orders.index') }}"><i class='bx bx-radio-circle'></i>All Orders</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-credit-card'></i>
                </div>
                <div class="menu-title">Payments</div>
            </a>
            <ul>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>All Payments</a> {{-- route('admin.payments.index') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Refunds</a> {{-- route('admin.payments.refunds') --}}
                </li>
            </ul>
        </li>

        <li class="menu-label">Promotions</li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-purchase-tag'></i>
                </div>
                <div class="menu-title">Coupons</div>
            </a>
            <ul>
                <li> <a href="{{ route('admin.coupons.index') }}"><i class='bx bx-radio-circle'></i>All Coupons</a>
                </li>
                <li> <a href="{{ route('admin.coupons.create') }}"><i class='bx bx-radio-circle'></i>Add New Coupon</a>
                </li>
            </ul>
        </li>



        <li class="menu-label">Communication</li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-bell'></i>
                </div>
                <div class="menu-title">Notifications</div>
            </a>
            <ul>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>All Notifications</a> {{-- route('admin.notifications.index') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Send Notification</a> {{-- route('admin.notifications.send') --}}
                </li>
            </ul>
        </li>

    </ul>
    <!--end navigation-->
</div>
<!--end sidebar wrapper -->
