<?php
defined('BASEPATH') OR exit('');
?>

<div class="pwell hidden-print">   
    <div class="row">
        <div class="col-sm-12">
            <!--- Row to create new transaction-->
            <div class="row">
                <div class="col-sm-6">
                    <span class="pointer text-primary">
                        <button class='btn btn-primary btn-sm' id='showTransForm'><i class="fa fa-plus"></i> New Transaction </button>
                    </span>
                </div>
            </div>
            <br>
            <!--- End of row to create new transaction-->
            <!---form to create new transactions--->
            <div class="row collapse" id="newTransDiv">
                <!---div to display transaction form--->
                <div class="col-sm-12" id="salesTransFormDiv">
                    <div class="well">
                        <form name="salesTransForm" id="salesTransForm" role="form">
                            <div class="text-center errMsg" id='newTransErrMsg'></div>
                            <br>

                            <div class="row">
                                <div class="col-sm-12">
                                    <!--Cloned div comes here--->
                                    <div id="appendClonedDivHere">
                                        <div class="row transItemList">
                                            <div class="col-sm-4 form-group-sm">
                                                <label>Item</label>
                                                <select class="form-control selectedItem" onchange="selectedItem(this)">
                                                    <option value="">Select Item</option>
                                                    <?php if(isset($items) && !empty($items)):?>
                                                    <?php foreach($items as $get):?>
                                                    <option value="<?=$get->code?>"><?=$get->name?></option>
                                                    <?php endforeach;?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>

                                            <div class="col-sm-2 form-group-sm itemAvailQtyDiv">
                                                <label>Available Quantity</label>
                                                <span class="form-control itemAvailQty">0</span>
                                            </div>

                                            <div class="col-sm-2 form-group-sm">
                                                <label>Unit Price</label>
                                                <span class="form-control itemUnitPrice">0.00</span>
                                            </div>

                                            <div class="col-sm-1 form-group-sm">
                                                <label>Quantity</label>
                                                <input type="number" min="0" class="form-control itemTransQty" value="0" onchange="ceipacp()">
                                                <span class="help-block itemTransQtyErr errMsg"></span>
                                            </div>

                                            <div class="col-sm-2 form-group-sm">
                                                <label>Total Price</label>
                                                <span class="form-control itemTotalPrice">0.00</span>
                                            </div>
                                            <br class="visible-xs">
                                            <div class="col-sm-1">
                                                <button class="close retrit">&times;</button>
                                            </div>
                                            <br class="visible-xs">
                                        </div>
                                    </div>
                                    <!--End of cloned div here--->
                                    
                                    <!--- Text to click to add another item to transaction-->
                                    <div class="row">
                                        <div class="col-sm-2 text-primary pointer" id="clickToClone">
                                            <button class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add another item</button>
                                        </div>
                                        
                                        <br class="visible-xs">
                                        
                                        <div class="col-sm-2 form-group-sm">
                                            <input type="text" id="barcodeText" class="form-control" placeholder="item code" autofocus>
                                            <span class="help-block errMsg" id="itemCodeNotFoundMsg"></span>
                                        </div>
                                    </div>
                                    <!--- End of text to click to add another item to transaction-->
                                    <br>
                                    
                                    <div class="row">
                                        <div class="col-sm-3 form-group-sm">
                                            <label for="modeOfPayment">Mode of Payment</label>
                                            <select class="form-control checkField" id="modeOfPayment">
                                                <option value="">---</option>
                                                <option value="Cash">Cash</option>
                                                <option value="POS">POS</option>
                                                <option value="Cash and POS">Cash and POS</option>
                                            </select>
                                            <span class="help-block errMsg" id="modeOfPaymentErr"></span>
                                        </div>
                                        
                                        <div class="col-sm-3 form-group-sm">
                                            <div class="cashAndPos hidden">
                                                <label for="cashAmount">Cash</label>
                                                <input type="text" class="form-control" id="cashAmount">
                                                <span class="help-block errMsg"></span>
                                            </div>

                                            <div class="cashAndPos hidden">
                                                <label for="posAmount">POS</label>
                                                <input type="text" class="form-control" id="posAmount">
                                                <span class="help-block errMsg"></span>
                                            </div>

                                            <div id="amountTenderedDiv">
                                                <label for="amountTendered" id="amountTenderedLabel">Amount Tendered</label>
                                                <input type="text" class="form-control" id="amountTendered">
                                                <span class="help-block errMsg" id="amountTenderedErr"></span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-2 form-group-sm">
                                            <label for="changeDue">Change Due</label>
                                            <span class="form-control" id="changeDue"></span>
                                        </div>
                                        
                                        <div class="col-sm-2 form-group-sm">
                                            <label for="vat">VAT(%)</label>
                                            <input type="number" min="0" id="vat" class="form-control" value="0">
                                        </div>
                                        
                                        <div class="col-sm-2 form-group-sm">
                                            <label for="cumAmount">Cumulative Amount</label>
                                            <span id="cumAmount" class="form-control">0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-sm-2 form-group-sm">
                                    <button class="btn btn-primary btn-sm" id='useScanner'>Use Barcode Scanner</button>
                                </div>
                                <br class="visible-xs">
                                <div class="col-sm-6"></div>
                                <br class="visible-xs">
                                <div class="col-sm-4 form-group-sm">
                                    <button type="button" class="btn btn-primary btn-sm" id="confirmSaleOrder">Confirm Order</button>
                                    <button type="button" class="btn btn-danger btn-sm" id="cancelSaleOrder">Clear Order</button>
                                    <button type="button" class="btn btn-danger btn-sm" id="hideTransForm">Close</button>
                                </div>
                            </div>
                        </form><!-- end of form-->
                    </div>
                </div>
                <!--- end of div to display transaction form--->
            </div>
            <!--end of form--->
    
            <br><br>
            <!-- sort and co row-->
            <div class="row">
                <div class="col-sm-12">
                    <!--<div class="col-sm-2 form-inline form-group-sm">
                        <label for="transType">Show</label>
                        <select class="form-control" id="transType">
                            <option>All</option>
                            <option>Sales</option>
                            <option>Purchases</option>
                        </select>
                    </div>--->

                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="transListPerPage">Per Page</label>
                        <select id="transListPerPage" class="form-control">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>

                    <div class="col-sm-5 form-group-sm form-inline">
                        <label for="transListSortBy">Sort by</label>
                        <select id="transListSortBy" class="form-control">
                            <option value="transId-DESC">date(Latest First)</option>
                            <option value="transId-ASC">date(Oldest First)</option>
                            <option value="quantity-DESC">Quantity (Highest first)</option>
                            <option value="quantity-ASC">Quantity (Lowest first)</option>
                            <option value="totalPrice-DESC">Total Price (Highest first)</option>
                            <option value="totalPrice-ASC">Total Price (Lowest first)</option>
                            <option value="totalMoneySpent-DESC">Total Amount Spent (Highest first)</option>
                            <option value="totalMoneySpent-ASC">Total Amount Spent (Lowest first)</option>
                        </select>
                    </div>

                    <div class="col-sm-4 form-inline form-group-sm">
                        <label for='transSearch'><i class="fa fa-search"></i></label>
                        <input type="search" id="transSearch" class="form-control" placeholder="Search Transactions">
                    </div>
                </div>
            </div>
            <!-- end of sort and co div-->
        </div>
    </div>
    
    <hr>
    
    <!-- transaction list table--->
    <div class="row">
        <!--- Transaction list div-->
        <div class="col-sm-12" id="transListTable"></div>
        <!--- End of transactions div-->
    </div>
    <!-- End of transactions list table--->
</div>


<!---End of copy of div to clone when adding more items to sales transaction---->
<script src="<?=base_url()?>public/js/transactions.js"></script>