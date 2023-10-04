<template>
	<div class="content-wrapper">

		<section class="content-header">

		</section>

		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">


						<div class="card">
							<div class="card-header">
								<form method="GET" name="form">
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<select name="designation_id" class="form-control">
													<option value="">User Type</option>
													<option value="1">Shop</option>
													<option value="2">Counter</option>
													<option value="3">Admin</option>
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<input type="text" class="form-control" name="email" value="" placeholder="example@test.com">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<button type="submit" class="btn btn-sm btn-primary">
													<i class="fa fa-search"></i> Search
												</button>
											</div>
										</div>
									</div>
								</form>
							</div>
							<!-- /.card-header -->
							<div class="card-body table-responsive p-0">
								<table class="table table-striped text-center table-hover">
									<thead>
										<tr>
											<th style="width:5%">#</th>
											<th>Name</th>
											<th>Role</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>

										<tr>
											<td> 5</td>
											<td>name</td>
											<td>Shop</td>
											<td>Active</td>
											<td>
												<a href="#" class="btn btn-xs bg-gradient-primary mr-1" title="এডিট">
													<i class="fas fa-edit"></i>
												</a>
												<a href="#" class="btn btn-xs bg-gradient-danger" title="এডিট">
													<i class="fas fa-trash"></i>
												</a>
											</td>
										</tr>
										<tr>
											<td> 5</td>
											<td>name</td>
											<td>Shop</td>
											<td>Active</td>
											<td>
												<a href="#" class="btn btn-xs bg-gradient-primary" title="এডিট">
													<i class="fas fa-edit"></i>
												</a>
												<a href="#" class="btn btn-xs bg-gradient-danger" title="এডিট">
													<i class="fas fa-trash"></i>
												</a>
											</td>
										</tr>

									</tbody>
								</table>
							</div>

							<!-- /.card-body -->
							<div class="card-footer clearfix">

								<div class="row">

									<div class="col-sm-12 col-md-6">
										দেখাচ্ছে 1 থেকে 50 এবং মোট 163 টি ফলাফল
									</div>

									<div class="col-sm-12 col-md-6">
										<div class="float-right">
											<nav>
												<ul class="pagination">
													<li class="page-item disabled" aria-disabled="true" aria-label="pagination.previous">
														<span class="page-link" aria-hidden="true">‹</span>
													</li>
													<li class="page-item">
														<a class="page-link" href="#">4</a>
													</li>
													<li class="page-item">
														<a class="page-link" href="#" rel="next" aria-label="pagination.next">›</a>
													</li>
												</ul>
											</nav>
										</div>
									</div>

								</div>

							</div>


						</div>

					</div>
				</div>
			</div>
		</section>


	</div>
</template>

<script>
export default {
    name:"user",
    data(){
        return {
            
        }
    }
}
</script>