<?php
    $url = $_SESSION["url"];
?>
<header>
    <!-- Sidebar -->
    <nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-white">
        <div class="position-sticky">
            <div class="list-group list-group-flush mx-3 mt-4">
                <a href="/" class="list-group-item list-group-item-action py-2 ripple <?= $url === "/"? 'active':'' ?>">
                    <i class="fas fa-tachometer-alt fa-fw me-3"></i>
                    <span>Main dashboard</span>
                </a>
                <a href="/home" class="list-group-item list-group-item-action py-2 ripple  <?= $url === "/home"? 'active':'' ?>">
                    <i class="fas fa-chart-area fa-fw me-3"></i>
                    <span>Home</span>
                </a>
                <a href="/posted" class="list-group-item list-group-item-action py-2 ripple  <?= $url === "/posted"? 'active':'' ?>">
                    <i class="fas fa-lock fa-fw me-3"></i>
                    <span>Posted</span>
                </a>
            </div>
        </div>
    </nav>
    <!-- Sidebar -->

</header>
<main style="margin-top: 58px;">
    <div class="container pt-4">
        <?php include $_ROUTER["layout_content"]; ?>
    </div>
</main>
