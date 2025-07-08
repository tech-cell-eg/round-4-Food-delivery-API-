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
                <li> <a href="#"><i class='bx bx-radio-circle'></i>All Admins</a> {{-- route('admin.admins.index') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Add New Admin</a> {{-- route('admin.admins.create') --}}
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
                <li> <a href="#"><i class='bx bx-radio-circle'></i>All Customers</a> {{-- route('admin.customers.index') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Add New Customer</a> {{-- route('admin.customers.create') --}}
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
                <li> <a href="#"><i class='bx bx-radio-circle'></i>All Chefs</a> {{-- route('admin.chefs.index') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Add New Chef</a> {{-- route('admin.chefs.create') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Pending Approval</a> {{-- route('admin.chefs.pending') --}}
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
                <li> <a href="#"><i class='bx bx-radio-circle'></i>All Categories</a> {{-- route('admin.categories.index') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Add New Category</a> {{-- route('admin.categories.create') --}}
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
                <li> <a href="#"><i class='bx bx-radio-circle'></i>All Dishes</a> {{-- route('admin.dishes.index') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Add New Dish</a> {{-- route('admin.dishes.create') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Featured Dishes</a> {{-- route('admin.dishes.featured') --}}
                </li>
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
                <li> <a href="#"><i class='bx bx-radio-circle'></i>All Orders</a> {{-- route('admin.orders.index') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Pending Orders</a> {{-- route('admin.orders.pending') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Processing Orders</a> {{-- route('admin.orders.processing') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Completed Orders</a> {{-- route('admin.orders.completed') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Cancelled Orders</a> {{-- route('admin.orders.cancelled') --}}
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
                <li> <a href="#"><i class='bx bx-radio-circle'></i>All Coupons</a> {{-- route('admin.coupons.index') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Add New Coupon</a> {{-- route('admin.coupons.create') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Expired Coupons</a> {{-- route('admin.coupons.expired') --}}
                </li>
            </ul>
        </li>

        <li class="menu-label">Analytics & Reports</li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-bar-chart-alt-2'></i>
                </div>
                <div class="menu-title">Reports</div>
            </a>
            <ul>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Sales Report</a> {{-- route('admin.reports.sales') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Orders Report</a> {{-- route('admin.reports.orders') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Customers Report</a> {{-- route('admin.reports.customers') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Chefs Report</a> {{-- route('admin.reports.chefs') --}}
                </li>
            </ul>
        </li>

        <li>
            <a href="#">
                <div class="parent-icon"><i class="bx bx-line-chart"></i>
                </div>
                <div class="menu-title">Analytics</div>
            </a> {{-- route('admin.analytics') --}}
        </li>

        <li class="menu-label">Communication</li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-message-dots'></i>
                </div>
                <div class="menu-title">Messages</div>
            </a>
            <ul>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>All Messages</a> {{-- route('admin.messages.index') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Unread Messages</a> {{-- route('admin.messages.unread') --}}
                </li>
            </ul>
        </li>

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

        <li class="menu-label">System</li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-cog'></i>
                </div>
                <div class="menu-title">Settings</div>
            </a>
            <ul>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>General Settings</a> {{-- route('admin.settings.general') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Payment Settings</a> {{-- route('admin.settings.payment') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Delivery Settings</a> {{-- route('admin.settings.delivery') --}}
                </li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Notification Settings</a> {{-- route('admin.settings.notifications') --}}
                </li>
            </ul>
        </li>

        <li>
            <a href="#">
                <div class="parent-icon"><i class="bx bx-user-circle"></i>
                </div>
                <div class="menu-title">Profile</div>
            </a> {{-- route('admin.profile') --}}
        </li>

        <li>
            <a href="#">
                <div class="parent-icon"> <i class="bx bx-time"></i>
                </div>
                <div class="menu-title">Activity Log</div>
            </a> {{-- route('admin.activity-log') --}}
        </li>

        <li class="menu-label">Support</li>

        <li>
            <a href="#">
                <div class="parent-icon"><i class="bx bx-help-circle"></i>
                </div>
                <div class="menu-title">Help & Support</div>
            </a> {{-- route('admin.help') --}}
        </li>

        <li>
            <a href="#">
                <div class="parent-icon"><i class="bx bx-folder"></i>
                </div>
                <div class="menu-title">Documentation</div>
            </a> {{-- route('admin.documentation') --}}
        </li>
    </ul>
    <!--end navigation-->
</div>
<!--end sidebar wrapper -->
