<!---->
<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: sara-->
<!-- * Date: 5/23/17-->
<!-- * Time: 10:15 AM-->
<!-- */-->
<div class="box box-info box-body">

    <div class="box-header with-border">
        <a class="btn btn-primary  pull-right" href="<?php echo base_url('Commodities/index');?>">Back</a>
    </div>
    <div class="col-md-6">
        <fieldset>
            <legend><b>Commodity Information</b></legend>


            <div class="form-group " align="left">
                <label class="col-sm-6 ">Name</label>
                <div class="input-group col-sm-6" align="left">
                    <?php echo $Commodity_details->name;?>&nbsp;
                </div>
            </div>

            <div class="form-group " align="left">
                <label class="col-sm-6 ">Creation Date</label>
                <div class="input-group col-sm-6" align="left">
                    <?php echo $Commodity_details->crated_on;?>&nbsp;
                </div>
            </div>

            <div class="form-group " align="left">
                <label class="col-sm-6">Updated Date </label>
                <div class="input-group col-sm-6" align="left">
                    <?php echo $Commodity_details->updated_on;?>&nbsp;
                </div>
            </div>

        </fieldset>
    </div>

</div>