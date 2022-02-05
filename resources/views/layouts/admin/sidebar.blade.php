<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="ulogo">
                <a href="index.html">
                    <!-- logo for regular state and mobile devices -->
                    <span><b>{{Setting::get('site_name')}}</b></span>
                </a>
            </div>
            <div class="image">
                <img class="rounded-circle" src="{{Auth::guard('admin')->user()->picture}}">
            </div>

            <div class="info">
                <p>{{Auth::guard('admin')->user()->name}}</p>
                <a href="{{route('admin.settings')}}" class="link" data-toggle="tooltip" title="" data-original-title="Settings"><i class="ion ion-gear-b"></i></a>

                <a href="{{route('admin.profile')}}" class="link" data-toggle="tooltip" title="" data-original-title="Account"><i class="ion ion-man"></i></a>

                <a data-toggle="modal" data-target="#logoutModel" href="{{route('admin.logout')}}" class="link" data-toggle="tooltip" title="" data-original-title="Logout"><i class="ion ion-power"></i></a>
            </div>
        </div>
        <!-- sidebar menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li id="dashboard">
                <a href="{{route('admin.dashboard')}}">
                    <i class="fa fa-dashboard"></i> 
                    <span  class="menu-title" data-i18n="">{{tr('dashboard')}}</span>
                </a>
            </li>

            <li class="nav-devider"></li>

            <li class="header nav-small-cap">{{tr('account_management')}}</li>

            <li class="treeview" id="users">
                <a href="#">
                    <i class="fa fa-user"></i>
                    <span>{{tr('users')}}</span>
                    <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li id="users-create">
                        <a href="{{route('admin.users.create')}}">
                            {{tr('add_user')}}
                        </a>
                    </li>
                    <li id="users-view">
                        <a href="{{route('admin.users.index')}}">
                            {{tr('view_users')}}
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-devider"></li>

            <li class="header nav-small-cap  text-uppercase">{{tr('project_management')}}</li>

            <li class="treeview" id="projects">
                <a href="{{route('admin.projects.index')}}">
                    <i class="glyphicon glyphicon-book"></i> <span>{{tr('projects')}}</span>    
                    <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>            
                </a>
                <ul class="treeview-menu">
                    <li id="projects-create">
                        <a href="{{route('admin.projects.create')}}">
                            {{tr('add_project')}}
                        </a>
                    </li>
                    <li id="projects-view">
                        <a href="{{route('admin.projects.index')}}">
                            {{tr('view_projects')}}
                        </a>
                    </li>
                </ul>
            </li>

            <!-- <li class="treeview" id="projects">
                <a href="#">
                    <i class="glyphicon glyphicon-book"></i>
                    <span>{{tr('projects')}}</span>
                    <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li id="projects-create">
                        <a href="{{route('admin.projects.create')}}">
                            {{tr('add_project')}}
                        </a>
                    </li>
                    <li id="projects-view">
                        <a href="{{route('admin.projects.index')}}">
                            {{tr('view_projects')}}
                        </a>
                    </li>
                </ul>
            </li> -->


            <li class="nav-item" id="invested-projects">
                <a href="{{route('admin.invested_projects')}}">
                    <i class="fa fa-database"></i>
                    <span class="menu-title" data-i18n="">{{tr('invested_projects')}}</span>
                </a>
            </li>


            <li class="nav-devider"></li>

            <li class="header nav-small-cap text-uppercase">{{tr('revenue_management')}}</li>

            <!-- <li class="nav-item" id="revenue-dashboard">
                <a href="{{route('admin.revenues.dashboard')}}">
                    <i class="fa fa-area-chart"></i>
                    <span class="menu-title" data-i18n="">{{tr('revenue_dashboard')}}</span>
                </a>
            </li> -->

            <li class="treeview" id="subscriptions">
                <a href="#">
                    <i class="fa fa-diamond"></i>
                    <span>{{tr('subscriptions')}}</span>
                    <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
                </a>

                <ul class="treeview-menu">

                    <li id="subscriptions-create">
                        <a href="{{route('admin.subscriptions.create')}}">
                            {{tr('add_subscription')}}
                        </a>
                    </li>

                    <li id="subscriptions-view">
                        <a href="{{route('admin.subscriptions.index')}}">
                            {{tr('view_subscriptions')}}
                        </a>
                    </li>
                </ul>
            </li>

            <!-- <li class="treeview" id="payments">
                <a href="#">
                    <i class="fa fa-credit-card"></i>
                    <span>{{tr('payments')}}</span>
                    <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
                </a>

                <ul class="treeview-menu">

                    <li id="subscription-payments">
                        <a href="{{route('admin.subscription_payments.index')}}">
                            {{tr('subscription_payments')}}
                        </a>
                    </li>

                    <li id="project-payments">
                        <a href="{{route('admin.project_payments.index')}}">
                            {{tr('project_owner_payments')}}
                        </a>
                    </li>
                    <li id="token-payments">
                        <a href="{{route('admin.token_payments.index')}}">
                            {{tr('token_payments')}}
                        </a>
                    </li>
                </ul>
            </li> -->

            <li class="nav-devider"></li>

            <li class="header nav-small-cap">{{tr('lookups_management')}}</li>

            <li class="nav-item" id="contact-forms">
                <a href="{{route('admin.contact_forms.index')}}">
                    <i class="fa fa-area-chart"></i>
                    <span class="menu-title" data-i18n="">{{tr('contact_forms')}}</span>
                </a>
            </li>

            <li class="treeview" id="documents" style="display: none;">
                <a href="{{route('admin.documents.index')}}">
                    <i class="icon-notebook"></i>
                    <span>{{tr('documents')}}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    <li id="documents-create">
                        <a href="{{route('admin.documents.create')}}">
                            {{tr('add_document')}}
                        </a>
                    </li>
                    <li id="documents-index">
                        <a href="{{route('admin.documents.index')}}">
                            {{tr('view_documents')}}
                        </a>
                    </li>
                </ul>
            </li>

            <li class="treeview" id="static_pages">
                <a href="#">
                    <i class="fa fa-support"></i>
                    <span>{{tr('static_pages')}}</span>
                    <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
                </a>

                <ul class="treeview-menu">

                    <li id="static_pages-create">
                        <a href="{{route('admin.static_pages.create')}}">
                            {{tr('add_static_page')}}
                        </a>
                    </li>
                    <li id="static_pages-view">
                        <a href="{{route('admin.static_pages.index')}}">
                            {{tr('view_static_pages')}}
                        </a>
                    </li>
                </ul>
            </li>

            <li class="treeview" id="faqs">
                <a href="#">
                    <i class="fa fa-question"></i>
                    <span>{{tr('faqs')}}</span>
                    <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
                </a>

                <ul class="treeview-menu">

                    <li id="faqs-create">
                        <a href="{{route('admin.faqs.create')}}">
                            {{tr('add_faq')}}
                        </a>
                    </li>
                    <li id="faqs-view">
                        <a href="{{route('admin.faqs.index')}}">
                            {{tr('view_faqs')}}
                        </a>
                    </li>
                </ul>
            </li>
            
            <li class="nav-devider"></li>

            <li class="header nav-small-cap">{{tr('setting_management')}}</li>

           
            <li id="settings">
                <a href="{{route('admin.settings')}}">
                    <i class="fa fa-cog"></i> <span>{{tr('settings')}}</span>                
                </a>
            </li>

            <li id="profile">
                <a href="{{route('admin.profile')}}">
                    <i class="fa fa-user"></i>
                    <span class="menu-title" data-i18n="">{{tr('account')}}</span>
                </a>
            </li>

            <li class="nav-item">
                <a data-toggle="modal" data-target="#logoutModel" href="{{route('admin.logout')}}">
                    <i class="fa fa-power-off"></i>
                    <span class="menu-title" data-i18n="">{{tr('logout')}}</span>
                </a>
            </li>

        </ul>
    </section>
</aside>