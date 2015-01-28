<section class="panel">
    <header class="panel-heading">
        expertrating Details
    </header>
    <div class="panel-body">
        <form class='form-horizontal tasi-form' method='post' action='<?php echo site_url("site/editexpertratingsubmit");?>' enctype='multipart/form-data'>
            <input type="hidden" id="normal-field" class="form-control" name="id" value="<?php echo set_value('id',$before->id);?>" style="display:none;">
            <div class=" form-group">
                <label class="col-sm-2 control-label" for="normal-field">Expert</label>
                <div class="col-sm-4">
                    <?php echo form_dropdown( "expert",$expert,set_value( 'expert',$before->expert),"class='chzn-select form-control'");?>
                </div>
            </div>
            <div class="form-group" style="display:none;">
                <label class="col-sm-2 control-label" for="normal-field">movie</label>
                <div class="col-sm-4">
                    <input type="text" id="normal-field" class="form-control" name="movie" value='<?php echo $movieid;?>'>
                </div>
            </div>
<!--
            <div class=" form-group">
                <label class="col-sm-2 control-label" for="normal-field">Movie</label>
                <div class="col-sm-4">
                    <?php echo form_dropdown( "movie",$movie,set_value( 'movie',$before->movie),"class='chzn-select form-control'");?>
                </div>
            </div>
-->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="normal-field">Rating</label>
                <div class="col-sm-4">
                    <input type="text" id="normal-field" class="form-control" name="rating" value='<?php echo set_value(' rating ',$before->rating);?>'>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="normal-field">&nbsp;</label>
                <div class="col-sm-4">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href='<?php echo site_url("site/viewpage"); ?>' class='btn btn-secondary'>Cancel</a>
                </div>
            </div>
        </form>
    </div>
</section>
