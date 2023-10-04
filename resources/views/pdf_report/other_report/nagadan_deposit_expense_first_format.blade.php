<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>{{ $page_title }}</title>
        <style>
			body{
				font-family: bangla;
			}
			@font-face {
				font-family: "SolaimanLipi";
				font-style: normal;
				font-weight: normal;
				src: url(SolaimanLipi.ttf) format('truetype');
			}
			* {
				font-family: "SolaimanLipi";
			}
			@page {
                margin: 0.5cm 1cm;
            }
			/* @page { margin:0px 25px; } */
			header,
			footer {
				left: 0cm;
                right: 0cm;
				position: fixed;
			}
			header,
			footer,
			.invoice-box,
			.header-table,
			.invoice-box table,
			.header-table table{
				font-size: 10px;
				line-height: 20px;
			}
			.invoice-box table td,
			.header-table table td{
				/* padding: 6px 8px; */
				vertical-align: top;
			}
			.table-design tr td:last-child,
			.header-table table tr td:last-child,
			.invoice-box table tr td:last-child{text-align: left;}
			.table-design{
				border-top: 1px solid #ddd;
				border-right: 1px solid #ddd;
			}
			/* page-break-inside: always; */


            /* .table-design tr{page-break-inside: auto;} */
			.table-design tr.heading td {
				background: #eee;
				border-bottom: 1px solid #ddd;
				font-weight: bold;
			}
			.table-design tr:nth-child(even) {background-color: #f9f9f9;}
			.table-design tr td{
				border-left: 1px solid #ddd;
				border-bottom: 1px solid #ddd;
			}
			.table-design tr.item td {
				border-bottom: 1px solid #ddd;
			}
			.table-design tr.item.last td {border-bottom: none;}
			.total-panel,
			.table-header-panel{
				border: 1px solid #ddd;
				background-color:#eeeeee;
				height: 38px;
				width: 100%;
			}
			
			.center {
				text-align: center;
			}
			.row {

			}
			.column {
				float: left;
				width: 50%;
			}
			/* Clearfix (clear floats) */
			.row::after {
				content: "";
				clear: both;
				display: table;
			}
			table {
				border-collapse: collapse;
				border-spacing: 0;
				width: 100%;
				border: 0;
			}
			/*
				top bar related design
			*/
			.top-bar-logo,
			.top-bar-info,
			.top-bar-time
			{
				float: left;
			}
			.top-bar-logo {
				width: 25%;
			}
			.top-bar-info {
				width: 50%;
			}
			.top-bar-time {
				width: 25%;
			}
			.text-center{
				text-align: center
			}
			.text-right{
				text-align: right
			}
			.text-left{
				text-align: left
			}
			.mb-3{
				margin-bottom: 10px
			}
			.float-right{
				float: right;
			}
		</style>
	</head>
	<body>
		<style>
			body {
				font-family: 'bangla', sans-serif;
			}
		</style>
		<main>
            <div class="top-bar">
				<div class="row mb-3">
					<div class="top-bar-logo text-left">&nbsp;</div>
					<div class="top-bar-info text-center">

						@if($page_header == 'only_company_name_in_header' || $page_header == 'all_info_in_header')
							@if(@setting('company.name'))
								<div style="font-weight: bolder; font-size: 24px; line-height: 24px">
									@setting('company.name')
								</div>
							@endif
						@endif
						
						@if($page_header == 'all_info_in_header')
							@if(@setting('company.slogan'))
								<div>@setting('company.slogan')</div>
							@endif

							@if(@setting('company.address'))
								<div style="font-size: 12px">
									@lang('cmn.address') : @setting('company.address')
								</div>
							@endif

							<div style="font-size: 12px">

								@if(@setting('company.phone'))
									@lang('cmn.phone'): @setting('company.phone')
								@endif

								@if(@setting('company.email'))
									@lang('cmn.email') : @setting('company.email')
								@endif

								@if(@setting('company.website'))
									@lang('cmn.web') : @setting('company.website')
								@endif

							</div>
						@endif

						<div style="font-size: 16px">
							{{ $header_title ?? '' }}
						</div>

					</div>
					<div class="top-bar-time text-right">
						<div style="font-size: 12px">
							@lang('cmn.reporting_time') {{ $reporting_time }}
						</div>
					</div>
				</div>
			</div>

			<div class="invoice-box">
				<div class="row">
					<table class="table-design" cellpadding="0" cellspacing="0">
						<tr class="center">
							<td class="center" width="5%">#</td>
							<td class="center" colspan="4">@lang('cmn.deposit')</td>
							<td class="center" width="5%">#</td>
							<td class="center" colspan="4">@lang('cmn.expense')</td>
						</tr>
						<tr>
							<td class="center" width="5%"></td>
							<td class="center" width="15%">@lang('cmn.description')</td>
							<td class="center" width="10%">@lang('cmn.previous_status')</td>
							<td class="center" width="10%">@lang('cmn.running')</td>
							<td class="center" width="10%">@lang('cmn.total')</td>
	
							<td class="center" width="5%"></td>
							<td class="center" width="15%">@lang('cmn.description')</td>
							<td class="center" width="10%">@lang('cmn.previous_status')</td>
							<td class="center" width="10%">@lang('cmn.running')</td>
							<td class="center" width="10%">@lang('cmn.total')</td>
						</tr>
					</table >
					<!-- deposit side -->
					<div class="column">
						<table class="table-design" cellpadding="0" cellspacing="0">
							<tr class="item">
								<td class="center" width="5%">1</td>
								<td width="35%">@lang('cmn.deposit_challan')</td>
								<td class="center" width="20%">{{ number_format($previous_deposit_challan)  }}</td>
								<td class="center" width="20%">{{ number_format($today_deposit_challan) }}</td>
								<td class="center" width="20%">{{ number_format($total_deposit_challan) }}</td>
							</tr>
							<tr class="item">
								<td class="center" width="5%">1</td>
								<td width="35%">@lang('cmn.commission_challan')</td>
								<td class="center" width="20%">{{ number_format($previous_commission_challan)  }}</td>
								<td class="center" width="20%">{{ number_format($today_commission_challan) }}</td>
								<td class="center" width="20%">{{ number_format($total_commission_challan) }}</td>
							</tr>

							@for($init=1; $init <= $row_count; $init++)
							<tr class="item">
								<td class="center">&nbsp;</td>
								<td class="center">&nbsp;</td>
								<td class="center">&nbsp;</td>
								<td class="center">&nbsp;</td>
								<td class="center">&nbsp;</td>
							</tr>
							@endfor

							<!-- total -->
							<tr class="item">
								<td class="center"></td>
								<td class="text-right">@lang('cmn.total')</td>
								<td class="center">{{ number_format($A1) }} @if($request['calculation'] == 'show')<b style="color: green;">(A1)</b>@endif</td>
								<td class="center">{{ number_format($A2) }} @if($request['calculation'] == 'show')<b style="color: green;">(A2)</b>@endif</td>
								<td class="center">{{ number_format($A3) }} @if($request['calculation'] == 'show')<b style="color: green;">(A3)</b>@endif</td>
							</tr>
							<tr class="item">
								<td class="center"></td>
								<td class="text-right">@lang('cmn.incoming_funds')</td>
								<td class="center">{{ number_format($B1) }} @if($request['calculation'] == 'show')<b style="color: green;">(B1)</b>@endif</td>
								<td class="center">{{ number_format($B2) }} @if($request['calculation'] == 'show')<b style="color: green;">(B2)</b>@endif</td>
								<td class="center">{{ number_format($B3) }} @if($request['calculation'] == 'show')<b style="color: green;">(B3)</b>@endif</td>
							</tr>
							<tr class="item">
								<td class="center"></td>
								<td class="text-right">@lang('cmn.total')</td>
								<td class="center">{{ number_format($C1) }} @if($request['calculation'] == 'show')<b style="color: green;">(C1)</b>@endif</td>
								<td class="center">{{ number_format($C2) }} @if($request['calculation'] == 'show')<b style="color: green;">(C2)</b>@endif</td>
								<td class="center">{{ number_format($C3) }} @if($request['calculation'] == 'show')<b style="color: green;">(C3)</b>@endif</td>
							</tr>
						</table>
					</div>

					<!-- expense side -->
					<div class="column">
						<table class="table-design" cellpadding="0" cellspacing="0">
							@php
								$expense_row_count = 1;
							@endphp
							<tr>
								<td class="center" width="5%">{{ $expense_row_count }}</td>
								<td class="center" width="35%">@lang('cmn.fuel')</td>
								<td class="center" width="20%">{{ number_format($previous_fuel)  }}</td>
								<td class="center" width="20%">{{ number_format($today_fuel) }}</td>
								<td class="center" width="20%">{{ number_format($total_fuel) }}</td>
							</tr>

							<!-- project expense -->
							@if(count($general_expenses) > 0)
								@foreach($general_expenses as $general_expense_key => $general_expense)
									@php
										$expense_row_count += 1;
									@endphp
									<tr>
										<td class="center">{{ $expense_row_count }}</td>
										<td class="center">{{ $general_expense->name }}</td>
										<td class="center">{{ number_format($general_expense->previous) }}</td>
										<td class="center">{{ number_format($general_expense->today) }}</td>
										<td class="center">{{ number_format($general_expense->total) }}</td>
									</tr>
								@endforeach
							@endif
							<tr>
								<td class="center">&nbsp;</td>
								<td class="center"></td>
								<td class="center"></td>
								<td class="center"></td>
								<td class="center"></td>
							</tr>
							<tr>
								<td class="center"></td>
								<td class="text-right">@lang('cmn.total')</td>
								<td class="center">{{ number_format($A4) }} @if($request['calculation'] == 'show')<b style="color: red;">(A4)</b>@endif</td>
								<td class="center">{{ number_format($A5) }}  @if($request['calculation'] == 'show')<b style="color: red;">(A5)</b>@endif</td>
								<td class="center">{{ number_format($A6) }}  @if($request['calculation'] == 'show')<b style="color: red;">(A6)</b>@endif</td>
							</tr>
							<tr>
								<td class="center"></td>
								<td class="text-right">@lang('cmn.cash_in_hand')</td>
								<td class="center">{{ number_format($B4) }}  @if($request['calculation'] == 'show')<b style="color: red;">(B4)</b>@endif</td>
								<td class="center">{{ number_format($B5) }}  @if($request['calculation'] == 'show')<b style="color: red;">(B5)</b>@endif</td>
								<td class="center">{{ number_format($B6) }}  @if($request['calculation'] == 'show')<b style="color: red;">(B6)</b>@endif</td>
							</tr>
							<tr>
								<td class="center"></td>
								<td class="text-right">@lang('cmn.total')</td>
								<td class="center">{{ number_format($C4) }}  @if($request['calculation'] == 'show')<b style="color: red;">(C4)</b>@endif</td>
								<td class="center">{{ number_format($C5) }}  @if($request['calculation'] == 'show')<b style="color: red;">(C5)</b>@endif</td>
								<td class="center">{{ number_format($C6) }}  @if($request['calculation'] == 'show')<b style="color: red;">(C6)</b>@endif</td>
							</tr>
						</table>
					</div>

					

					@if($request['calculation'] == 'show')
					<br>
                    <br>
                    <br>
                    <br>
                    <br>
					<h1>

						# @lang('cmn.deposit_challan') + @lang('cmn.commission_challan') +   ডাইনামিক ডিপোজিট = A1, A2, A3 <br>
						----------------------------------------------------------------------------- <br>
						<b style="color: green;">(A1)</b> গতকাল পর্যন্ত সমস্ত জমা (কাভার্ড ভ্যান প্রকল্প + ডাইনামিক ডিপোজিট) = {{ number_format($A1) }} <br>
						<b style="color: green">(A2)</b> আজকের মোট জমা (কাভার্ড ভ্যান প্রকল্প + ডাইনামিক ডিপোজিট) = {{ number_format($A2) }} <br>
						<b style="color: green">(A3)</b> A1 + A2 = {{ number_format($A3) }}<br>
						<br>
						<br>

						# আগত তহিবল = B1, B2, B3 <br>
						----------------------------------------------------------------------------- <br>
						<b style="color: green">(B1)</b> ফিক্সিড শূন্য হবে = 0 <br>
						<b style="color: green">(B2)</b> আগের দিন পর্যন্ত হাতে নগদ = A1 - A4 = {{ number_format($B2) }}<br>
						<b style="color: green">(B3)</b> ফিক্সিড শূন্য হবে = 0 <br>
						<br>
						<br>

						# জমা ইন টোটাল = C1, C2, C3 <br>
						----------------------------------------------------------------------------- <br>
						<b style="color: green">(C1)</b> গতকাল পর্যন্ত সমস্ত জমা (কাভার্ড ভ্যান প্রকল্প + ডাইনামিক ডিপোজিট) = A1 = {{ number_format($C1) }}<br>
						<b style="color: green">(C2)</b> B2 + A2  = {{ number_format($C2) }}<br>
						<b style="color: green">(C3)</b> A3 = {{ number_format($C3) }}<br>
						<br>
						<br>

						# খরচ = A4, A5, A6 <br>
						----------------------------------------------------------------------------- <br>
						<b style="color: red;">(A4)</b> গতকাল পর্যন্ত সমস্ত খরচ = {{ number_format($A4) }}<br>
						<b style="color: red;">(A5)</b> আজকের মোট খরচ = {{ number_format($A5) }}<br>
						<b style="color: red;">(A6)</b> A4 + A5 = {{ number_format($A6) }}<br>
						<br>
						<br>

						# খরচ = B4, B5, B6 <br>
						----------------------------------------------------------------------------- <br>
						<b style="color: red;">(B4)</b> B2 = {{ number_format($B4) }}<br>
						<b style="color: red;">(B5)</b> (B4 + A2) - A5 = {{ number_format($B5) }}<br>
						<b style="color: red;">(B6)</b> C3 - A6 =  {{ number_format($B6) }}<br>
						<br>
						<br>

						# খরচ ইন টোটাল = C4, C5, C6 <br>
						----------------------------------------------------------------------------- <br>
						<b style="color: red;">(C4)</b> B4 + A4 = {{ number_format($C4) }}<br>
						<b style="color: red;">(C5)</b> A5 + ((B4 + A2) - A5) = {{ number_format($C5) }}<br>
						<b style="color: red;">(C6)</b> C3 - A6 - A6 = {{ number_format($C6) }}<br>
					</h1>
					@endif

					
				</div>
			</div>

		</main>
	</body>
</html>