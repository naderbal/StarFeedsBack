 <ul class="nav navbar-nav" >
     <li class="@yield('editActive')" class="dropdown">
         <a href="#" class="dropdown-toggle" data-toggle="dropdown">
             <strong>Edit</strong>
             <span class="glyphicon glyphicon-chevron-down"></span>
         </a>
         <ul class="dropdown-menu" style="border: none; background-color: #eee; ">
             <li class="@yield('viewCelebritiesActive')"><a href="/admin/viewCelebrity"><strong style="color:#333">View Celebrities</strong></a></li>
             <li class="divider"></li>
             <li class="@yield('editCelebritiesActive')"><a href="/admin/editCelebrity"><strong  style="color:#333">Edit Celebrity</strong></a></li>
             <li class="divider"></li>
             <li class="@yield('addCelebritiesActive')"><a href="/admin/addCelebrity"><strong  style="color:#333">Add Celebrity</strong></a></li>
         </ul>
     </li>
 </ul>