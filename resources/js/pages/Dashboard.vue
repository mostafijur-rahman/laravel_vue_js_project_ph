<template>
	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
			</div>
		</section>
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6">
						<div class="card">
							<div class="card-body">
								<Bar
									id="my-chart-id"
									:options="chartOptions"
									:data="chartData"
								/>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card">
							<div class="card-header">
								<h3 class="card-title">{{ $t('challan') }} {{ $t('summary') }}</h3>
							</div>
							<div class="card-body table-responsive p-0">
								<table class="table table-striped table-bordered text-center table-hover text-nowrap">
									<thead>
										<tr>
											<th>{{ $t('type') }}</th>
											<th>{{ $t('qty') }}</th>

											<th>{{ $t('deposit') }}</th>
											<th>{{ $t('expense') }}</th>
											<th>{{ $t('oil_expense') }}</th>
											<th>{{ $t('balance') }}</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>{{ $t('deposit_challan') }}</td>
											<td>0</td>

											<td>0</td>
											<td>0</td>
											<td>0</td>
											<td>0</td>
										</tr>
										<tr>
											<td>{{ $t('up_down_deposit_challan') }}</td>
											<td>0</td>

											<td>0</td>
											<td>0</td>
											<td>0</td>
											<td>0</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td>{{ $t('total') }} = </td>
											<td><b>0</b></td>

											<td><b>0</b></td>
											<td><b>0</b></td>
											<td><b>0</b></td>
											<td><b>0</b></td>
										</tr>
									</tfoot>

								</table>
							</div>
						</div>
					</div>
				</div>

				<div class="row">

					<div class="col-md-6">
						<div class="card">

							<div class="card-header">
								<h3 class="card-title">{{ $t('challan') }} {{ $t('summary') }}</h3>
							</div>

							<div class="card-body table-responsive p-0">
								<table class="table table-striped table-bordered text-center table-hover text-nowrap">
									<thead>
										<tr>
											<th>{{ $t('type') }}</th>
											<th>{{ $t('qty') }}</th>

											<th>{{ $t('deposit') }}</th>
											<th>{{ $t('expense') }}</th>
											<th>{{ $t('oil_expense') }}</th>
											<th>{{ $t('balance') }}</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>{{ $t('deposit_challan') }}</td>
											<td>0</td>

											<td>0</td>
											<td>0</td>
											<td>0</td>
											<td>0</td>
										</tr>
										<tr>
											<td>{{ $t('up_down_deposit_challan') }}</td>
											<td>0</td>

											<td>0</td>
											<td>0</td>
											<td>0</td>
											<td>0</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td>{{ $t('total') }} = </td>
											<td><b>0</b></td>

											<td><b>0</b></td>
											<td><b>0</b></td>
											<td><b>0</b></td>
											<td><b>0</b></td>
										</tr>
									</tfoot>

								</table>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="card">

							<div class="card-header">
								<h3 class="card-title">{{ $t('challan') }} {{ $t('summary') }}</h3>
							</div>

							<div class="card-body table-responsive p-0">
								<table class="table table-striped table-bordered text-center table-hover text-nowrap">
									<thead>
										<tr>
											<th>{{ $t('type') }}</th>
											<th>{{ $t('qty') }}</th>

											<th>{{ $t('deposit') }}</th>
											<th>{{ $t('expense') }}</th>
											<th>{{ $t('oil_expense') }}</th>
											<th>{{ $t('balance') }}</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>{{ $t('deposit_challan') }}</td>
											<td>0</td>

											<td>0</td>
											<td>0</td>
											<td>0</td>
											<td>0</td>
										</tr>
										<tr>
											<td>{{ $t('up_down_deposit_challan') }}</td>
											<td>0</td>

											<td>0</td>
											<td>0</td>
											<td>0</td>
											<td>0</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td>{{ $t('total') }} = </td>
											<td><b>0</b></td>

											<td><b>0</b></td>
											<td><b>0</b></td>
											<td><b>0</b></td>
											<td><b>0</b></td>
										</tr>
									</tfoot>

								</table>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</section>

	</div>
</template>

<script>

import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

export default {
    name:"dashboard",
	components: { 
		Bar 
	},
    data(){
        return {
            user: this.$store.state.auth.user,

			chartData: {
				labels: [
					'January',
					'February',
					'March',
					'April',
					'May',
					'Jun',
					'July',
					'August',
					'September', 
					'Octeber',
					'November',
					'December'
				],
				datasets: [ 
					{
						label: this.$t('deposit'),
						backgroundColor: '#28a745',
						data: [20, 25, 30, 35, 32, 45, 38, 33, 39, 41, 42, 45],
					},
					{ 
						label: this.$t('due'),
						backgroundColor: '#ffadad',
						data: [15, 18, 28, 25, 21, 26, 31, 15, 17, 16, 19, 21],
					},
					{
						label: this.$t('expense'),
						backgroundColor: '#ffc107',
						data: [18, 22, 15, 17, 16, 22, 19, 16, 18, 20, 21, 22],
					}
				]
			},
			chartOptions: {
				responsive: true
			}
        }
    },
	created(){
		this.getUser();
	},
	methods: {
		getUser(){
			this.listLoading = true;
			axios.get('/api/auth-user')
				.then((response) => {
					// console.log(response.data);
				}).catch((error) => {
					this.toast.warning(error.message);
				}).finally(() =>{
					this.listLoading = false;
				});
		}
	}
}
</script>