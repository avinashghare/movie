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
                movie Details
            </header>
            <div class="panel-body">
                <form class='form-horizontal tasi-form' method='post' action='<?php echo site_url("site/createmoviesubmit");?>' enctype='multipart/form-data'>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="normal-field">Name</label>
                            <div class="col-sm-4">
                                <input type="text" id="normal-field" class="form-control" name="name" value='<?php echo set_value(' name ');?>'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="normal-field">Duration</label>
                            <div class="col-sm-4">
                                <input type="text" id="normal-field" class="form-control" name="duration" value='<?php echo set_value(' duration ');?>'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="normal-field">Dateofrelease</label>
                            <div class="col-sm-4">
                                <input type="date" id="normal-field" class="form-control" name="dateofrelease" value='<?php echo set_value(' dateofrelease ');?>'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="normal-field">Rating</label>
                            <div class="col-sm-4">
                                <input type="text" id="normal-field" class="form-control" name="rating" value='<?php echo set_value(' rating ');?>'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="normal-field">Director</label>
                            <div class="col-sm-4">
                                <input type="text" id="normal-field" class="form-control" name="director" value='<?php echo set_value(' director ');?>'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="normal-field">Writer</label>
                            <div class="col-sm-4">
                                <input type="text" id="normal-field" class="form-control" name="writer" value='<?php echo set_value(' writer ');?>'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="normal-field">Casteandcrew</label>
                            <div class="col-sm-4">
                                <input type="text" id="normal-field" class="form-control" name="casteandcrew" value='<?php echo set_value(' casteandcrew ');?>'>
                            </div>
                        </div>
                        <div class=" form-group">
                            <label class="col-sm-2 control-label" for="normal-field">Summary</label>
                            <div class="col-sm-8">
                                <textarea name="summary" id="" cols="20" rows="10" class="form-control tinymce"><?php echo set_value( 'summary');?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="normal-field">Twittertrack</label>
                            <div class="col-sm-4">
                                <input type="text" id="normal-field" class="form-control" name="twittertrack" value='<?php echo set_value(' twittertrack ');?>'>
                            </div>
                        </div>
                        <div class=" form-group">
                            <label class="col-sm-2 control-label" for="normal-field">Trailer</label>
                            <div class="col-sm-4">
                                <input type="file" id="normal-field" class="form-control" name="trailer" value=value="<?php echo set_value('trailer');?>">
                            </div>
                        </div>
                        <div class=" form-group">
                            <label class="col-sm-2 control-label" for="normal-field">Isfeatured</label>
                            <div class="col-sm-4">
                                <?php echo form_dropdown("isfeatured",$isfeatured,set_value('isfeatured'), "class='chzn-select form-control'");?>
                            </div>
                        </div>
                        <div class=" form-group">
                            <label class="col-sm-2 control-label" for="normal-field">Isintheator</label>
                            <div class="col-sm-4">
                                <?php echo form_dropdown("isintheator",$isintheator,set_value('isintheator'), "class='chzn-select form-control'");?>
                            </div>
                        </div>
                        <div class=" form-group">
                            <label class="col-sm-2 control-label" for="normal-field">Iscommingsoon</label>
                            <div class="col-sm-4">
                                <?php echo form_dropdown("iscommingsoon",$iscommingsoon,set_value('iscommingsoon'), "class='chzn-select form-control'");?>
                            </div>
                        </div>
                        
                        <div class=" form-group">
                          <label class="col-sm-2 control-label">Genre</label>
                          <div class="col-sm-4">
                            <?php

                                echo form_dropdown('genre[]',$genre,set_value('genre'),'id="select3" class="chzn-select form-control" 	data-placeholder="Choose an Genre..." multiple');
                            ?>
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
