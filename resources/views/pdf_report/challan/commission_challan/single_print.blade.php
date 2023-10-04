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
				font-size: 12px;
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
				width: 20%;
			}
			.top-bar-info {
				width: 60%;
			}
			.top-bar-time {
				width: 20%;
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
						<div style="font-size: 10px">
							@lang('cmn.reporting_time') {{ $reporting_time }}
						</div>
					</div>
				</div>
			</div>

			<div class="invoice-box">
				<table class="table-design" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="20%" class="text-center">@lang('cmn.primary_info')</td>
                        <td width="20%" class="text-center">@lang('cmn.transection_with_vehicle_supplier')</td>
                        <td width="20%" class="text-center">@lang('cmn.transection_with_company')</td>
                        <td width="20%" class="text-center">@lang('cmn.commission')</td>
                    </tr>
                    @if($list)
                    <tr>
                        <td class="text-left">
                            @lang('cmn.challan_number'): <b>{{ $list['challan_number'] }}</b><br>
                            @lang('cmn.start_date'): <b>{{ $list['start_date_time'] ? $list['start_date_time'] : '' }}</b><br>

                            @lang('cmn.vehicle'): <b>{{ $list['rental_vehicle'] ? $list['rental_vehicle']['vehicle_number'] : '' }}</b><br>
                            @lang('cmn.driver'): <b>{{ $list['rental_vehicle'] ? $list['rental_vehicle']['driver_name'] : '' }}</b><br>
                            @lang('cmn.reference'): <b>{{ $list['rental_vehicle'] ? $list['rental_vehicle']['reference_name'] : '' }}</b><br>
                            @lang('cmn.owner'): <b>{{ $list['rental_vehicle'] ? $list['rental_vehicle']['owner_name'] : '' }}</b><br>

                            @lang('cmn.load_point'): <b>{{ $list->load? $list->load :'---' }}</b><br>
                            @lang('cmn.unload_point'): <b>{{ $list->unload? $list->unload :'---' }}</b><br>

                            @lang('cmn.buyer_name'): <b>{{ $list['buyer_name'] ? $list['buyer_name'] : '' }}</b><br>
                            @lang('cmn.buyer_code'): <b>{{ $list['buyer_code'] ? $list['buyer_code'] : '' }}</b><br>
                            @lang('cmn.order_no'): <b>{{ $list['order_no'] ? $list['order_no'] : '' }}</b><br>
                            @lang('cmn.depu_change_bill'): <b>{{ $list['depu_change_bill'] ? $list['depu_change_bill'] : '' }}</b><br>
                            @lang('cmn.gate_pass_no'): <b>{{ $list['gate_pass_no'] ? $list['gate_pass_no'] : '' }}</b><br>
                            @lang('cmn.lock_no'): <b>{{ $list['lock_no'] ? $list['lock_no'] : '' }}</b><br>
                            @lang('cmn.load_point_reach_time'): <b>{{ $list['load_point_reach_time'] ? $list['load_point_reach_time'] : '' }}</b><br>
                            @lang('cmn.box_qty'): <b>{{ $list['box'] ? $list['box'] : '' }}</b><br>
                            @lang('cmn.weight'): <b>{{ $list['weight'] ? $list['weight'] : '' }}</b><br>
                            @lang('cmn.unit'): <b>{{ $list['unit_info'] ? __('cmn.' . $list['unit_info']['name']) : '' }}</b><br>
                            @lang('cmn.goods'): <b>{{ $list['goods'] ? $list['goods'] : '' }}</b><br>
                            @lang('cmn.note'): <b>{{ $list['note'] ? $list['note'] : '' }}</b><br>

                            @lang('cmn.posted_by'): <br>
                            <b>{{ $list['created_info']['first_name'] }} ({{ $list['created_at'] }})</b><br>
            
                            @if($list['updated_by'])
                                @lang('cmn.post_updated_by'): <br>
                                <b>{{ $list['updated_info']['first_name'] }} ({{ $list['updated_at'] }})</b><br>
                            @endif
                        </td>
                        <td class="text-right">
                            @if($list['rental_vehicle'] && $list['rental_vehicle']['vendor'])

								<b>{{ $list['rental_vehicle']['vendor']['name'] }}</b><br>
								@if(count($list['vendor_bills']) > 0)
										@lang('cmn.contract_rent') = {{ number_format(array_sum(array_column($list['vendor_contract_bills']->ToArray(), 'amount'))) }}<br>
										@lang('cmn.demurrage_rent') = {{ number_format(array_sum(array_column($list['vendor_demurrage_bills']->ToArray(), 'amount'))) }}<br>
									---------------------------------------------- <br>
										@lang('cmn.total_rent') = {{ number_format(array_sum(array_column($list['vendor_bills']->ToArray(), 'amount'))) }}<br>
										@lang('cmn.advance_provide') = (-) {{ number_format(array_sum(array_column($list['vendor_advance_payments']->ToArray(), 'amount'))) }}<br>
										@lang('cmn.after_advance_provide') = (-) {{ number_format(array_sum(array_column($list['vendor_after_advance_payments']->ToArray(), 'amount'))) }}<br>
									---------------------------------------------- <br>
									@lang('cmn.challan_due') = {{ number_format(
											array_sum(array_column($list['vendor_bills']->ToArray(), 'amount')) - 
											array_sum(array_column($list['vendor_payments']->ToArray(), 'amount'))
										) 
									}}
								@endif

							@else
                                @lang('cmn.no_information_provided')
                            @endif
                        </td>
                        <td class="text-right">
                            @if(count($list['client_bills']) > 0)

								<b>{{ $list['client_bills'][0]['client_info']['name'] }}</b><br>

									@lang('cmn.contract_rent') = {{ number_format(array_sum(array_column($list['client_contract_bills']->ToArray(), 'amount'))) }}<br>
									@lang('cmn.demurrage_rent') = {{ number_format(array_sum(array_column($list['client_demurrage_bills']->ToArray(), 'amount'))) }}<br>
								---------------------------------------------- <br>
									@lang('cmn.total_rent') ={{ number_format(array_sum(array_column($list['client_bills']->ToArray(), 'amount'))) }}<br>
									@lang('cmn.advance_received') = (-) {{ number_format(array_sum(array_column($list['client_advance_payments']->ToArray(), 'amount'))) }}<br>
									@lang('cmn.after_advance_rent') = (-) {{ number_format(array_sum(array_column($list['client_after_advance_payments']->ToArray(), 'amount'))) }}<br>
								---------------------------------------------- <br>
								@lang('cmn.challan_due') = {{ number_format(
										array_sum(array_column($list['client_bills']->ToArray(), 'amount')) - 
										array_sum(array_column($list['client_payments']->ToArray(), 'amount'))
									) 
								}}
                            @else
                                @lang('cmn.no_information_provided')
                            @endif
                        </td>
                        <td class="text-right">
                            @lang('cmn.contract_commission') = {{ number_format(
									array_sum(array_column($list['client_bills']->ToArray(), 'amount')) - 
									array_sum(array_column($list['vendor_bills']->ToArray(), 'amount'))
								) 
							}}
                            <br>
                            <br>
                            @lang('cmn.received_from_company') = {{ number_format(array_sum(array_column($list['client_payments']->ToArray(), 'amount'))) }}<br>
                            @lang('cmn.rental_paid') = {{ number_format(array_sum(array_column($list['vendor_payments']->ToArray(), 'amount'))) }}<br>
                            ---------------------------------------------- <br>
                            @lang('cmn.commission_balance') = {{ number_format(
								array_sum(array_column($list['client_payments']->ToArray(), 'amount')) - 
								array_sum(array_column($list['vendor_payments']->ToArray(), 'amount'))
							) 
						}}
                        </td>
                    </tr>
                    @else
                        <tr>
                            <td colspan="5" class="text-center text-red"><h4>@lang('cmn.empty_table')</h4>
                        </tr>
                    @endif
                </table>
			</div>

		</main>
	</body>
</html>