<div class="row" style="padding:1% 0">
    <div class="col-md-12">
        <div class="pull-right">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                userrate Details
            </header>
            <div class="panel-body">
                <form class='form-horizontal tasi-form' method='post' action='<?php echo site_url("site/createuserratesubmit");?>' enctype='multipart/form-data'>
                    <div class="panel-body">
                       
                        <div class="form-group"  style="display:none">
                            <label class="col-sm-2 control-label" for="normal-field">user</label>
                            <div class="col-sm-4">
                                <input type="text" id="normal-field" class="form-control" name="user" value='<?php echo $userid;?>'>
                            </div>
                        </div>
                        <div class=" form-group">
                            <label class="col-sm-2 control-label" for="normal-field">Movie</label>
                            <div class="col-sm-4">
                                <?php echo form_dropdown( "movie",$movie,set_value( 'movie'), "class='chzn-select form-control'");?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="normal-field">Rating</label>
                            <div class="col-sm-4">
                                <input type="text" id="normal-field" class="form-control" name="rating" value='<?php echo set_value(' rating ');?>'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="normal-field">&nbsp;</label>
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="<?php echo site_url(" site/viewpage "); ?>" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                </form>
                </div>
        </section>
        </div>
    </div>
