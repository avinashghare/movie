<section class="panel">

    <div class="panel-body">
        <ul class="nav nav-stacked">
            <li><a href="<?php echo site_url('site/edituser?id=').$before->id; ?>">User Details</a></li>
            <li><a href="<?php echo site_url('site/viewuserlike?id=').$before->id; ?>">Likes</a></li>
            <li><a href="<?php echo site_url('site/viewuserrate?id=').$before->id; ?>">Ratings</a></li>
            <li><a href="<?php echo site_url('site/viewusercomment?id=').$before->id; ?>">Comment</a></li>
            <li><a href="<?php echo site_url('site/viewuserrecommend?id=').$before->id; ?>">Recommend</a></li>
        </ul>
    </div>
</section>