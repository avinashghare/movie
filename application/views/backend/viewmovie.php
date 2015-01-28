<div class="row" style="padding:1% 0">
    <div class="col-md-12">
        <a class="btn btn-primary pull-right" href="<?php echo site_url("site/createmovie"); ?>"><i class="icon-plus"></i>Create </a> &nbsp;
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                movie Details
            </header>
            <div class="drawchintantable">
                <?php $this->chintantable->createsearch("movie List");?>
                <table class="table table-striped table-hover" id="" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th data-field="id">ID</th>
                            <th data-field="name">Name</th>
                            <th data-field="duration">Duration</th>
                            <th data-field="dateofrelease">Dateofrelease</th>
                            <th data-field="rating">Rating</th>
                            <th data-field="director">Director</th>
                            <th data-field="writer">Writer</th>
                            <th data-field="casteandcrew">Casteandcrew</th>
<!--
                            <th data-field="summary">Summary</th>
                            <th data-field="twittertrack">Twittertrack</th>
                            <th data-field="trailer">Trailer</th>
                            <th data-field="isfeatured">Isfeatured</th>
                            <th data-field="isintheator">Isintheator</th>
                            <th data-field="iscommingsoon">Iscommingsoon</th>
-->
                            <th data-field="action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <?php $this->chintantable->createpagination();?>
            </div>
        </section>
        <script>
            function drawtable(resultrow) {
                return "<tr><td>" + resultrow.id + "</td><td>" + resultrow.name + "</td><td>" + resultrow.duration + "</td><td>" + resultrow.dateofrelease + "</td><td>" + resultrow.rating + "</td><td>" + resultrow.director + "</td><td>" + resultrow.writer + "</td><td>" + resultrow.casteandcrew + "</td><td><a class='btn btn-primary btn-xs' href='<?php echo site_url('site/editmovie?id=');?>" + resultrow.id + "'><i class='icon-pencil'></i></a><a class='btn btn-danger btn-xs' href='<?php echo site_url('site/deletemovie?id='); ?>" + resultrow.id + "'><i class='icon-trash '></i></a></td></tr>";
            }
            generatejquery("<?php echo $base_url;?>");
        </script>
<!--
        <script>
            function drawtable(resultrow) {
                return "<tr><td>" + resultrow.id + "</td><td>" + resultrow.name + "</td><td>" + resultrow.duration + "</td><td>" + resultrow.dateofrelease + "</td><td>" + resultrow.rating + "</td><td>" + resultrow.director + "</td><td>" + resultrow.writer + "</td><td>" + resultrow.casteandcrew + "</td><td>" + resultrow.summary + "</td><td>" + resultrow.twittertrack + "</td><td>" + resultrow.trailer + "</td><td>" + resultrow.isfeatured + "</td><td>" + resultrow.isintheator + "</td><td>" + resultrow.iscommingsoon + "</td><td><a class='btn btn-primary btn-xs' href='<?php echo site_url('site/editmovie?id=');?>" + resultrow.id + "'><i class='icon-pencil'></i></a><a class='btn btn-danger btn-xs' href='<?php echo site_url('site/deletemovie?id='); ?>" + resultrow.id + "'><i class='icon-trash '></i></a></td></tr>";
            }
            generatejquery("<?php echo $base_url;?>");
        </script>
-->
    </div>
</div>
