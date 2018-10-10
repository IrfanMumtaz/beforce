@extends('admin/layouts/default')

@section('title')
Daily Sale Report
@parent
@stop

@section('content')
<style type="text/css">
    a.export,
a.export:visited {
  margin-left: 1%;
}
/*! CSS Used from: https://beem.solutions/brandedge_col/admin/assets/cache/master_main.css */
*{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
table{border-collapse:collapse;border-spacing:0;}
.wojo.form ::-webkit-input-placeholder{color:#AAA;}
.wojo.form ::-moz-placeholder{color:#AAA;}
.wojo.form :focus::-webkit-input-placeholder{color:#999;}
.wojo.form :focus::-moz-placeholder{color:#999;}
/*! CSS Used from: Embedded */
.report-table{background:#fff;}
.report-table td,.report-table th{padding:5px 10px;border:1px solid #ccc;text-align:center;}
</style>
<section class="content-header">
    <h1>SKU</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li>Reports</li>
        <li class="active">Daily Sale Report </li>
    </ol>
</section>
<section class="content paddingleft_right15">
<div class="row">
    @include('flash::message')
 <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"> <i class="livicon" data-name="user" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                Daily Sale Report
            </h4></div>
        <br />
        <div class="panel-body">
            {!! Form::open(['route' => 'admin.reports.daily_sale_report','autocomplete'=>'off', 'method'=>'post']) !!}
                    <div class="form-group col-md-3 col-xs-3 col-sm-3">
                        {!! Form::label('brands', 'Select brand:') !!}
                        {!! Form::Select('brands', $brands, null, ['class' => 'form-control','id'=>'brands']) !!}
                        {!! $errors->first('brands', '<p class="alert alert-danger">:message</p>') !!}
                    </div>
                    <div class="form-group col-md-3 col-xs-3 colsm-3">
                        <label>Date From: </label>
                        @if(isset($selected_from))
                        <input type="date" id='from' name="from" value="{{$selected_from}}" class="form-control" placeholder="Date From" max="{{date('Y-m-d')}}">
                        {!! $errors->first('from', '<p class="alert alert-danger">:message</p>') !!}
                        @else
                        <input type="date" id='from' name="from" class="form-control" max="{{date('Y-m-d')}}" placeholder="Date From" >
                        {!! $errors->first('from', '<p class="alert alert-danger">:message</p>') !!}
                        @endif
                    </div>
                    <div class="form-group col-md-3 col-xs-3 colsm-3">
                        <label>Date To: </label>
                        @if(isset($selected_to))
                        <input type="date" class="form-control" value="{{$selected_to}}" id="to" name="to" placeholder="Date To" max="{{date('Y-m-d')}}" >
                        {!! $errors->first('to', '<p class="alert alert-danger">:message</p>') !!}
                        @else
                        <input type="date" class="form-control" id="to" name="to" placeholder="Date To" max="{{date('Y-m-d')}}">
                        {!! $errors->first('to', '<p class="alert alert-danger">:message</p>') !!}
                        @endif
                    </div>
                    <div class="col-md-3">
                        <label> </label>
                        {!! Form::submit('Filter', ['class' => 'btn btn-primary','id'=>'daily_sale_submit_btn']) !!}
                    </div>
            {!! Form::close() !!}
            <hr>
    </div>
@if(isset($results))
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<a class="btn btn-primary export csv"><img src = "{{ asset('assets/js/libs/icons/csv.png') }}" width="15%"; height="15%"> Export to Excel</a>
<a class="btn btn-primary export png"><img src = "{{ asset('assets/js/libs/icons/png.png') }}" width="15%"; height="15%"> Export to Image</a>
<div class="scrollmenu" id="dvData">
    @php ($c=0)
    @if(count($results)>0)
    @foreach($results as $result)
    @php($city=$result['city'])
    @php($c++)
    @php($shop_ba=array())
    @if(count($result['shops'])>0)
    <div id="DIV_1254">     
                                        <table id="TABLE_1" class="text-center">
                                            <tbody id="TBODY_2">
                                                <tr id="TR_3">
                                                    <th colspan="3" class="text-center" spans="3" id="TH_4">
                                                        {!! $result['city'] !!}
                                                    </th>
                                                    <th id="TH_5"  class="text-center" spans="3">
                                                        Total
                                                    </th>
                                                    <th id="TH_6"  class="text-center" spans="3">
                                                        Consolidated (Value)
                                                    </th>
                                                </tr>
                                                @if(count($result['BA'])>0)
                                                @for($i=0; $i<count($result['shops']); $i++)
                                                @php($shop=$result['shops'][$i])
                                                @if(isset($result['BA'][$shop]))
                                                <tr id="TR_7">
                                                    <td id="TD_8" rspans='3'>
                                                        {!! $result['city'] !!}
                                                    </td>
                                                    <td id="TD_9" rspans='3'>
                                                        {!! $result['shops'][$i] !!}
                                                    </td>
                                                    <td id="TD_10" rspans='3'>
                                                        @if(isset($result['BA'][$shop]))
                                                        @php($shop_ba[]=$result['BA'][$shop])
                                                        {!! $result['BA'][$shop] !!}
                                                        @endif
                                                    </td>
                                                    <td id="TD_11" mtable='1'>
                                                        <table id="TABLE_12" class="tableic">
                                                            <tbody id="TBODY_13">
                                                                <tr id="TR_14">
                                                                    <th id="TH_15" class="text-center" spn='3'>
                                                                        Interception
                                                                    </th>
                                                                    <th id="TH_16" class="text-center" spn='3'>
                                                                        Conversion
                                                                    </th>
                                                                    <th id="TH_17" class="text-center" spn='3'>
                                                                        % ACH
                                                                    </th>
                                                                </tr>
                                                                <tr id="TR_18">
                                                                    <td id="TD_19">
                                                                        @if(isset($result['interception'][$shop]))
                                                                        {!! $result['interception'][$shop] !!}
                                                                        @else
                                                                        0
                                                                        @endif
                                                                    </td>
                                                                    <td id="TD_20">
                                                                        @if(isset($result['coversion'][$shop]))
                                                                        {!! $result['coversion'][$shop] !!}
                                                                        @else
                                                                        0
                                                                        @endif
                                                                    </td>
                                                                    <td id="TD_21">
                                                                        @if(isset($result['ic_achieve'][$shop]))
                                                                        {!! $result['ic_achieve'][$shop] !!}
                                                                        @endif
                                                                        %
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td id="TD_22">
                                                        <table id="TABLE_23">
                                                            <tbody id="TBODY_24">
                                                                <tr id="TR_25">
                                                                    <th id="TH_26" class="text-center">
                                                                        Total Target
                                                                    </th>
                                                                    <th id="TH_27" class="text-center">
                                                                        Total Sale
                                                                    </th>
                                                                    <th id="TH_28" class="text-center">
                                                                        % ACH
                                                                    </th>
                                                                </tr>
                                                                <tr id="TR_29">
                                                                    <td id="TD_30">
                                                                        {!! $result['total_targets'][$shop] !!}
                                                                    </td>
                                                                    <td id="TD_31">
                                                                        {!! $result['total_sales'][$shop] !!}
                                                                    </td>
                                                                    <td id="TD_32">
                                                                        {!! $result['ts_achieve'][$shop] !!}%
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                        @for($j=0; $j<count($result['skus']); $j++)
                                                        @php($sku=$result['skus'][$j])
                                                    <td id="TD_33">
                                                        <table id="TABLE_34">
                                                            <tbody id="TBODY_35">
                                                                <tr id="TR_36">
                                                                    <th colspan="3" class="text-center" id="TH_37">
                                                                        {!! $result['skus'][$j] !!}
                                                                    </th>
                                                                </tr>
                                                                <tr id="TR_38">
                                                                    <th id="TH_39" class="text-center">
                                                                        Target
                                                                    </th>
                                                                    <th id="TH_40" class="text-center">
                                                                        Sale
                                                                    </th>
                                                                    <th id="TH_41" class="text-center">
                                                                        % ACH
                                                                    </th>
                                                                </tr>
                                                                <tr id="TR_42">
                                                                    <td id="TD_43">
                                                                        @if(isset($result['skutargets'][$shop][$sku])) 
                                                                        {!! $result['skutargets'][$shop][$sku] !!}
                                                                        @else
                                                                        0
                                                                        @endif
                                                                    </td>
                                                                    <td id="TD_44">
                                                                        @if(isset($result['skusales'][$shop][$sku])) 
                                                                        {!! $result['skusales'][$shop][$sku] !!}
                                                                        @else
                                                                        0
                                                                        @endif
                                                                    </td>
                                                                    <td id="TD_45">
                                                                        @if(isset($result['sku_achieve'][$shop][$sku])) 
                                                                        {!! $result['sku_achieve'][$shop][$sku] !!}%
                                                                        @else
                                                                        0%
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                        @endfor
                                                        @endif
                                                </tr>
                                                @endfor
                                                @else
                                                <tr>
                                                  <td colspan="6"><h5 class="text-center">No Record Found In {!! $result['city'] !!}</h5></td>
                                                </tr>
                                                @endif
                                                @if(count($shop_ba)>0)
                                                <tr id="TR_7">
                                                    <td id="TD_8" colspan="3">
                                                        Over All Totals
                                                    </td>
                                                    <td id="TD_11">
                                                        <table id="TABLE_12">
                                                            <tbody id="TBODY_13">
                                                                <tr id="TR_14">
                                                                    <th id="TH_15" class="text-center">
                                                                        Interception
                                                                    </th>
                                                                    <th id="TH_16" class="text-center">
                                                                        Conversion
                                                                    </th>
                                                                    <th id="TH_17" class="text-center">
                                                                        % ACH
                                                                    </th>
                                                                </tr>
                                                                <tr id="TR_18">
                                                                    <td id="TD_19">
                                                                        @if(isset($result['total_interception'][$city]))
                                                                        {!! $result['total_interception'][$city] !!}
                                                                        @endif
                                                                    </td>
                                                                    <td id="TD_20">
                                                                        @if(isset($result['total_coversion'][$city]))
                                                                        {!! $result['total_coversion'][$city] !!}
                                                                        @else
                                                                        0
                                                                        @endif
                                                                    </td>
                                                                    <td id="TD_21">
                                                                        @if(isset($result['total_ic_achieve'][$city]))
                                                                        {!! $result['total_ic_achieve'][$city] !!}
                                                                        @endif
                                                                        %
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td id="TD_22">
                                                        <table id="TABLE_23">
                                                            <tbody id="TBODY_24">
                                                                <tr id="TR_25">
                                                                    <th id="TH_26" class="text-center">
                                                                        Total Target
                                                                    </th>
                                                                    <th id="TH_27" class="text-center">
                                                                        Total Sale
                                                                    </th>
                                                                    <th id="TH_28" class="text-center">
                                                                        % ACH
                                                                    </th>
                                                                </tr>
                                                                <tr id="TR_29">
                                                                    <td id="TD_30">
                                                                        {!! $result['overall_total_targets'][$city] !!}
                                                                    </td>
                                                                    <td id="TD_31">
                                                                        {!! $result['overall_total_sales'][$city] !!}
                                                                    </td>
                                                                    <td id="TD_32">
                                                                        {!! $result['overall_ts_achieve'][$city] !!}%
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                        @for($j=0; $j<count($result['skus']); $j++)
                                                        @php($sku=$result['skus'][$j])
                                                    <td id="TD_33">
                                                        <table id="TABLE_34">
                                                            <tbody id="TBODY_35">
                                                                <tr id="TR_36">
                                                                    <th colspan="3" class="text-center" id="TH_37">
                                                                        {!! $result['skus'][$j] !!}
                                                                    </th>
                                                                </tr>
                                                                <tr id="TR_38">
                                                                    <th id="TH_39" class="text-center">
                                                                        Target
                                                                    </th>
                                                                    <th id="TH_40" class="text-center">
                                                                        Sale
                                                                    </th>
                                                                    <th id="TH_41" class="text-center">
                                                                        % ACH
                                                                    </th>
                                                                </tr>
                                                                <tr id="TR_42">
                                                                    <td id="TD_43">
                                                                        @if(isset($result['overall_skutargets'][$city][$sku])) 
                                                                        {!! $result['overall_skutargets'][$city][$sku] !!}
                                                                        @else
                                                                        0
                                                                        @endif
                                                                    </td>
                                                                    <td id="TD_44">
                                                                        @if(isset($result['overall_skusales'][$city][$sku])) 
                                                                        {!! $result['overall_skusales'][$city][$sku] !!}
                                                                        @else
                                                                        0
                                                                        @endif
                                                                    </td>
                                                                    <td id="TD_45">
                                                                        @if(isset($result['overall_sku_achieve'][$city][$sku])) 
                                                                        {!! $result['overall_sku_achieve'][$city][$sku] !!}%
                                                                        @else
                                                                        0%
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                        @endfor
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>

                                    </div>
                                    @endif
                                    @endforeach
                                    <table class="report-table" style="margin: 1%;">
                                    <tbody><tr>
                                        <td colspan="3">National Total</td>
                                        <td>
                                            <table>
                                                <tbody><tr>
                                                    <th style="border: 1px solid #000000;background-color: #F7FAFC;">
                                                        Interception
                                                    </th>
                                                    <th style="border: 1px solid #000000;background-color: #F7FAFC;">
                                                        Conversion
                                                    </th>
                                                    <th style="border: 1px solid #000000;background-color: #F7FAFC;">
                                                        % ACH
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>@if(isset($grand_interception))
                                                        {!! $grand_interception !!}
                                                        @else
                                                        0
                                                        @endif</td>
                                                    <td>@if(isset($grand_conversion))
                                                        {!! $grand_conversion !!}
                                                        @else
                                                        0
                                                        @endif</td>
                                                    <td>@if(isset($grand_ic_achieve))
                                                        {!! $grand_ic_achieve !!}%
                                                        @else
                                                        0%
                                                        @endif</td>
                                                </tr>
                                            </tbody></table>
                                        </td>

                                        <td>
                                            <table>
                                                <tbody><tr>
                                                    <th style="border: 1px solid #000000;background-color: #F7FAFC;">
                                                        Total Target
                                                    </th>
                                                    <th style="border: 1px solid #000000;background-color: #F7FAFC;">
                                                        Total Sale
                                                    </th>
                                                    <th style="border: 1px solid #000000;background-color: #F7FAFC;">
                                                        % ACH
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>{!! $grand_total_targets !!}</td>
                                                    <td>{!! $grand_total_sales !!}</td>
                                                    <td>{!! $grand_ts_achieve !!}%</td>
                                                </tr>
                                            </tbody></table>
                                        </td>
                                            @for($j=0; $j<count($result['skus']); $j++)
                                            @php($sku=$result['skus'][$j])
                                            <td>
                                                 <table>
                                                     <tbody><tr>
                                                         <th colspan="3" style="border: 1px solid #000000;background-color: #F7FAFC;">{!! $result['skus'][$j] !!}</th>
                                                     </tr>
                                                     <tr>
                                                         <th>Target</th>
                                                         <th>Sale</th>
                                                         <th>% ACH</th>
                                                     </tr>
                                                     <tr>
                                                         <td>@if(isset($grand_skutargets[$sku]))
                                                            {!! $grand_skutargets[$sku]  !!}
                                                            @else
                                                            SKU name is not set
                                                            @endif
                                                         </td>
                                                         <td>@if(isset($grand_skusales[$sku]))
                                                            {!! $grand_skusales[$sku]  !!}
                                                            @else
                                                            SKU name is not set
                                                            @endif</td>
                                                         <td>@if(isset($grand_sku_achieve[$sku]))
                                                            {!! $grand_sku_achieve[$sku]  !!}%
                                                            @else
                                                            SKU name is not set
                                                            @endif</td>
                                                     </tr>

                                                 </tbody></table>

                                             </td>
                                             @endfor
                                    </tr>
                                </tbody></table>
                                <hr>
                                    @else
                                    <div class="container">
                                      <hr>
                                      <h4 class="text-center">No Result Found</h4>
                                      <hr>
                                    </div>
                                    @endif
                                    </div>
                                    @endif
                                          </div>
 </div>
</section>
 @stop
@section('footer_scripts')
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var d = new Date();
            var month = d.getMonth()+1;
            var day = d.getDate();

            var output = d.getFullYear() + '-' +
                (month<10 ? '0' : '') + month + '-' +
                (day<10 ? '0' : '') + day;
            var report='daily-sale-report-'+output;
            $('#brands').on('change',function(){
                $('#from').val(output);
                $('#to').val(output);
            });
            $('#daily_sale_submit_btn').css('width','100%');
            $('#html').css('overflow-x','visible');
            $('.export').css('margin-left','1%');
            $(document.body).css('overflow-x','visible');
            $('.csv').on('click',function(){
                $('#dvData').tableExport({type:'excel',fileName: report,excelstyles: ['background-color', 'color',
                                                                                         'border-bottom-color', 'border-bottom-style', 'border-bottom-width',
                                                                                         'border-top-color', 'border-top-style', 'border-top-width',
                                                                                         'border-left-color', 'border-left-style', 'border-left-width',
                                                                                         'border-right-color', 'border-right-style', 'border-right-width',
                                                                                         'font-family', 'font-size', 'font-weight']});
            });
            $('.png').on('click',function(){
                $('#dvData').tableExport({type:'png',fileName: report});
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {

  function exportTableToCSV($table, filename) {

    var $rows = $table.find('tr:has(td)'),

      // Temporary delimiter characters unlikely to be typed by keyboard
      // This is to avoid accidentally splitting the actual contents
      tmpColDelim = String.fromCharCode(11), // vertical tab character
      tmpRowDelim = String.fromCharCode(0), // null character

      // actual delimiter characters for CSV format
      colDelim = '","',
      rowDelim = '"\r\n"',

      // Grab text from table into CSV formatted string
      csv = '"' + $rows.map(function(i, row) {
        var $row = $(row),
          $cols = $row.find('td');

        return $cols.map(function(j, col) {
          var $col = $(col),
            text = $col.text();

          return text.replace(/"/g, '""'); // escape double quotes

        }).get().join(tmpColDelim);

      }).get().join(tmpRowDelim)
      .split(tmpRowDelim).join(rowDelim)
      .split(tmpColDelim).join(colDelim) + '"';

    // Deliberate 'false', see comment below
    if (false && window.navigator.msSaveBlob) {

      var blob = new Blob([decodeURIComponent(csv)], {
        type: 'text/csv;charset=utf8'
      });

      // Crashes in IE 10, IE 11 and Microsoft Edge
      // See MS Edge Issue #10396033
      // Hence, the deliberate 'false'
      // This is here just for completeness
      // Remove the 'false' at your own risk
      window.navigator.msSaveBlob(blob, filename);

    } else if (window.Blob && window.URL) {
      // HTML5 Blob        
      var blob = new Blob([csv], {
        type: 'text/csv;charset=utf-8'
      });
      var csvUrl = URL.createObjectURL(blob);

      $(this)
        .attr({
          'download': filename,
          'href': csvUrl
        });
    } else {
      // Data URI
      var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

      $(this)
        .attr({
          'download': filename,
          'href': csvData,
          'target': '_blank'
        });
    }
  }

});
    </script>
   
@stop