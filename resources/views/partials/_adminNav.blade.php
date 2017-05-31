 <ul class="nav navbar-nav" >
     <li class="@yield('editActive') dropdown">
         <a href="#" class="dropdown-toggle" data-toggle="dropdown">
             <strong>Admin</strong>
             <span class="caret"></span>
         </a>
         <ul class="dropdown-menu" style="border: none; background-color: #eee; ">

             <li class="@yield('editCelebritiesActive')"><a href="/admin/edit-celebrity"><strong  style="color:#333">Edit Celebrity</strong></a></li>
             <li class="divider"></li>
             <li class="@yield('addCelebritiesActive')"><a href="/admin/add-celebrity"><strong  style="color:#333">Add Celebrity</strong></a></li>
             <li class="divider"></li>
             <li class="@yield('addAdminActive')"><a href="/admin/add-admin"><strong  style="color:#333">Add Admin</strong></a></li>
             <li class="divider"></li>
             <li class="@yield('messagesActive')"><a href="/admin/messages"><strong  style="color:#333">Messages</strong></a></li>
         </ul>
     </li>
 </ul>