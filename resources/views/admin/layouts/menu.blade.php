
<style>
  ul li a{
    color: white !important;
  }
</style>

<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #bf0a30;">
    <!-- Brand Logo -->
 
    <a href="{{route('admin.home')}}" class="brand-link" style="text-align:justify; color:white;">
    <img src="{{asset('admin')}}/img/leftlogo.png" alt="HasPanel Logo" class="brand-image img-circle elevation-3" >
     <strong class="brand-text font-weight-light" style=""> <span style="font-weight: 700;">BENILDE  Centre</span></strong>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      {{-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('admin')}}/img/mypic.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{$auth->name." ".$auth->surname }}</a>
        </div>
      </div> --}}

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ route('admin.home') }}" class="nav-link @if(Request::segment(2)=="home") active @endif">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>{{ __('main.Dashboard') }}</p>
            </a>
          </li>

          <li class="nav-item has-treeview @if(Request::segment(2)=="mainmenu" || Request::segment(2)=="submenu")  menu-open @endif">
            <a href="{{ route('admin.mainmenu.index') }}" class="nav-link @if(Request::segment(2)=="mainmenu" || Request::segment(2)=="submenu") active @endif">
              <i class="nav-icon fas fa-plus-circle"></i>
              <p>
                {{ __('main.Menus') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="@if(Request::segment(2)=="media") display:block; @endif">
              <li class="nav-item">
                <a href="{{ route('admin.mainmenu.index') }}" class="nav-link @if(Request::segment(2)=="mainmenu") active @endif">
                    <ion-icon name="return-down-forward-outline"></ion-icon>
                    <p>{{ __('main.mainmenu') }}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.submenu.index') }}" class="nav-link @if(Request::segment(2)=="submenu") active @endif">
                    <ion-icon name="return-down-forward-outline"></ion-icon>
                    <p>{{ __('main.submenu') }}</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview @if(Request::segment(2)=="media") menu-open @endif">
            <a href="{{ route('admin.media.index') }}" class="nav-link @if(Request::segment(2)=="media") active @endif">
              <i class="nav-icon fas fa-photo-video"></i>
              <p>
                {{ __('main.Media') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="@if(Request::segment(2)=="media") display:block; @endif">
              <li class="nav-item">
                <a href="{{ route('admin.media.index') }}" class="nav-link @if(Request::segment(2)=="media" && Request::segment(3)!="create") active @endif">
                    <ion-icon name="return-down-forward-outline"></ion-icon>
                    <p>{{ __('main.All Media') }}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.media.create') }}" class="nav-link @if(Request::segment(2)=="media" && Request::segment(3)=="create") active @endif">
                    <ion-icon name="return-down-forward-outline"></ion-icon>
                    <p>{{ __('main.Upload') }}</p>
                </a>
              </li>
            </ul>
          </li>
          
          <li class="nav-item has-treeview @if(Request::segment(2)=="page") menu-open @endif">
            <a href="{{ route('admin.page.index') }}" class="nav-link @if(Request::segment(2)=="page") active @endif">
                <i class="nav-icon fas fa-copy"></i>
              <p>
                {{ __('main.Pages') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="@if(Request::segment(2)=="page") display:block; @endif">
                <li class="nav-item">
                  <a href="{{ route('admin.page.index') }}" class="nav-link @if(Request::segment(2)=="page"&& Request::segment(3)!="create") active @endif">
                    <ion-icon name="return-down-forward-outline"></ion-icon>
                    <p>{{ __('main.All Pages') }}</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('admin.page.create') }}" class="nav-link @if(Request::segment(2)=="page" && Request::segment(3)=="create") active @endif">
                    <ion-icon name="return-down-forward-outline"></ion-icon>
                    <p>{{ __('main.Add New') }}</p>
                  </a>
                </li>
            </ul>
          </li>
          <li class="nav-item has-treeview @if(Request::segment(2)=="resource" || Request::segment(3)=="room") menu-open @endif">
            <a href="{{ route('admin.resource.index') }}" class="nav-link @if(Request::segment(2)=="resource" ||  Request::segment(3)=="room") active @endif">
              <i class="nav-icon fas fa-building"></i>
                <p>
                    {{ __('main.resources') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="@if(Request::segment(2)=="resource") display:block; @endif">
              <li class="nav-item">
                <a href="{{ route('admin.room.category') }}" class="nav-link @if(Request::segment(3)=="room" && Request::segment(2)=="category") active @endif">
                  <ion-icon name="return-down-forward-outline"></ion-icon>
                  <p>{{ __('main.Categories') }}</p>
                </a>
              </li>
                <li class="nav-item">
                  <a href="{{ route('admin.resource.index') }}" class="nav-link @if(Request::segment(2)=="resource" && Request::segment(3)!="create") active @endif">
                    <ion-icon name="return-down-forward-outline"></ion-icon>
                    <p>{{ __('main.allresources') }}</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('admin.resource.create') }}" class="nav-link @if(Request::segment(2)=="resource" && Request::segment(3)=="create") active @endif">
                    <ion-icon name="return-down-forward-outline"></ion-icon>
                    <p>{{ __('main.addresources') }}</p>
                  </a>
                </li>
            </ul>
          </li>
          <li class="nav-item has-treeview @if(Request::segment(2)=="activitie"  || Request::segment(3)=='services') menu-open @endif ">
            <a href="{{ route('admin.activitie.index') }}" class="nav-link @if(Request::segment(2)=="activitie" || Request::segment(3)=='services') active @endif">
              <i class="fas fa-snowboarding nav-icon"></i>
                <p>
                    {{ __('main.Activities') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="@if(Request::segment(2)=="resource") display:block; @endif">
              <li class="nav-item">
                <a href="{{ route('admin.services.category') }}" class="nav-link @if(Request::segment(3)=="services" && Request::segment(2)=="category") active @endif">
                  <ion-icon name="return-down-forward-outline"></ion-icon>
                  <p>{{ __('main.Categories') }}</p>
                </a>
              </li>
                <li class="nav-item">
                  <a href="{{ route('admin.activitie.index') }}" class="nav-link @if(Request::segment(2)=="activitie" && Request::segment(3)!="create") active @endif">
                    <ion-icon name="return-down-forward-outline"></ion-icon>
                    <p>{{ __('main.allActivities') }}</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('admin.activitie.create') }}" class="nav-link @if(Request::segment(2)=="resource" && Request::segment(3)=="create") active @endif">
                    <ion-icon name="return-down-forward-outline"></ion-icon>
                    <p>{{ __('main.addrActivities') }}</p>
                  </a>
                </li>
            </ul>
          </li>
          <li class="nav-item has-treeview @if(Request::segment(2)=="update" || Request::segment(3)=='update') menu-open @endif ">
            <a href="{{ route('admin.update.index') }}" class="nav-link @if(Request::segment(2)=="update" || Request::segment(3)=='update') active @endif">
              <i class="fas fa-sync-alt nav-icon"></i>
                <p>
                    {{ __('main.update') }}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview" style="@if(Request::segment(2)=="update") display:block; @endif">
                <li class
                ="nav-item">
                    <a href="{{ route('admin.update.category') }}" class="nav-link @if(Request::segment(3)=="update" || Request::segment(2)=="category") active @endif">
                        <ion-icon name="return-down-forward-outline"></ion-icon>
                        <p>{{ __('main.Categories') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.update.index') }}" class="nav-link @if(Request::segment(2)=="update" && Request::segment(3)!="create") active @endif">
                        <ion-icon name="return-down-forward-outline"></ion-icon>
                        <p>{{ __('main.Allupdate') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.update.create') }}" class="nav-link @if(Request::segment(2)=="update" && Request::segment(3)=="create") active @endif">
                        <ion-icon name="return-down-forward-outline"></ion-icon>
                        <p>{{ __('main.Addupdate') }}</p>
                    </a>
                </li>
            </ul>
        </li>
          <li class="nav-item has-treeview @if(Request::segment(2)=="gallery" || Request::segment(3)== "gallery") menu-open @endif">
            <a href="{{ route('admin.gallery.index') }}" class="nav-link @if(Request::segment(2)=="gallery" || Request::segment(3)== "gallery") active @endif">
              <i class="nav-icon fas fa-image"></i>
              <p>
                {{ __('main.Gallery') }}
                
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="@if(Request::segment(2)=="gallery") display:block; @endif">
              <li class="nav-item">
                <a href="{{ route('admin.gallery.category') }}" class="nav-link @if(Request::segment(3)=="gallery" || Request::segment(2)=="category") active @endif">
                  <ion-icon name="return-down-forward-outline"></ion-icon>
                  <p>{{ __('main.Categories') }}</p>
                </a>
              </li>
              <li class="nav-item"> 
                <a href="{{ route('admin.gallery.index') }}" class="nav-link @if(Request::segment(2)=="gallery" && Request::segment(3)!="create") active @endif">
                    <ion-icon name="return-down-forward-outline"></ion-icon>
                    <p>{{ __('main.All Images') }}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.gallery.create') }}" class="nav-link @if(Request::segment(2)=="gallery" && Request::segment(3)=="create") active @endif">
                    <ion-icon name="return-down-forward-outline"></ion-icon>
                    <p>{{ __('main.Upload') }}</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.slide.index') }}" class="nav-link @if(Request::segment(2)=="slide") active @endif">
                <i class="fas fa-expand nav-icon"></i>
              <p>{{ __('main.Slides') }}</p>
            </a>
          </li>

          <li class="nav-item has-treeview @if(Request::segment(2)=="ourfeature"  || Request::segment(3)=='features') menu-open @endif ">
            <a href="{{ route('admin.ourfeature.index') }}" class="nav-link @if(Request::segment(2)=="ourfeature" || Request::segment(3)=='features') active @endif">
              <i class="fas fa-users nav-icon"></i>
                <p>
                    {{ __('main.ourfeature') }}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview" style="@if(Request::segment(2)=="ourfeature") display:block; @endif">
                <li class="nav-item">
                    <a href="{{ route('admin.features.category') }}" class="nav-link @if(Request::segment(3)=="features" || Request::segment(2)=="category") active @endif">
                        <ion-icon name="return-down-forward-outline"></ion-icon>
                        <p>{{ __('main.Categories') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.ourfeature.index') }}" class="nav-link @if(Request::segment(2)=="ourfeature" && Request::segment(3)!="create") active @endif">
                        <ion-icon name="return-down-forward-outline"></ion-icon>
                        <p>{{ __('main.Allourfeature') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.ourfeature.create') }}" class="nav-link @if(Request::segment(2)=="ourfeature" && Request::segment(3)=="create") active @endif">
                        <ion-icon name="return-down-forward-outline"></ion-icon>
                        <p>{{ __('main.Addourfeature') }}</p>
                    </a>
                </li>
            </ul>
        </li>

          
          <li class="nav-item">
            <a href="{{ route('admin.testimonial.index') }}" class="nav-link @if(Request::segment(2)=="testimonial") active @endif">
                 <i class="fas fa-quote-left nav-icon"></i>
              <p>{{ __('main.testimonials') }}</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.contact.index') }}" class="nav-link @if(Request::segment(2)=="contact") active @endif">
                <i class="fas fa-phone nav-icon"></i>
              <p>{{ __('main.Contact Request') }}</p>
            </a>
          </li>

          {{-- <li class="nav-item">
            <a href="{{ route('admin.social.index') }}" class="nav-link @if(Request::segment(2)=="social") active @endif">
              <i class="fab fa-youtube nav-icon"></i>
              <p>{{ __('main.socialmedia') }}</p>
            </a>
          </li> --}}

          
        <li class="nav-item has-treeview @if(Request::segment(2)=="option" ) menu-open @endif">
            <a href="{{ route('admin.option.index') }}" class="nav-link @if(Request::segment(2)=="option") active @endif">
                <i class=" nav-icon fas fa-cog"></i>
                <p>
                    {{ __('main.Options') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="@if(Request::segment(2)=="option") display:block; @endif">
               <li class="nav-item">
                  <a href="{{ route('admin.option.contact') }}" class="nav-link @if(Request::segment(2)=="option" && Request::segment(3)=="contact") active @endif">
                    <ion-icon name="return-down-forward-outline"></ion-icon>
                    <p>{{ __('main.Contact Information') }}</p>
                  </a>
                </li>
            </ul>
          </li>
         

          <li class="nav-item">
            <form action="{{route('logout')}}" method="POST">
              @csrf
              <a href="{{route('logout')}}" class="nav-link" onclick="event.preventDefault(); this.closest('form').submit();">
                <i class="nav-icon fas fa-power-off"></i>
                {{ __('main.Logout') }}
              </a>
            </form>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
