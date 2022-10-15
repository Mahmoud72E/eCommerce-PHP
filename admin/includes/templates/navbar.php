<nav class="navbar navbar-expand-lg bg-dark">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php"><?php echo lang('Home')?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="categories.php"><?php echo lang('Categories')?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Items.php"><?php echo lang('Items')?></a>
        </li>
        <li class="nav-item"> 
          <a class="nav-link" href="members.php"><?php echo lang('Members')?></a> 
        </li>
        <li class="nav-item"> 
          <a class="nav-link" href="comments.php"><?php echo lang('Comments')?></a> 
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Mahmoud
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item" href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>"><?php echo lang('Edit Profile')?></a></li>
            <li><a class="dropdown-item" href="../index.php"><?php echo lang('Visit Shop')?></a></li>
            <li><a class="dropdown-item" href="#"><?php echo lang('Setting')?></a></li>
            <li><a class="dropdown-item" href="logout.php"><?php echo lang('Logout')?></a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>                    