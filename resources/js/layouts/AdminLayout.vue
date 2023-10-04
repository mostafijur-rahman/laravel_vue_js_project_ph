<template>
	<!-- for login panel -->
	<div 
		v-if="!authenticated"
		style="height: 100%; width: 100%; background-position: center; background-repeat: no-repeat; background-size: cover;"
		:style="{'background-image':`url(${fileNameWithPath})`}"
	>
		<router-view></router-view>
	</div>

	<!-- for admin panel -->
	<div class="wrapper" v-if="authenticated">
		<NavBar />
		<MainSideBar />
		<router-view></router-view>
		<!-- <Footer /> -->
		<aside class="control-sidebar control-sidebar-dark"></aside>
	</div>
</template>

<script>

	import MainSideBar from '../components/MainSideBar.vue';
	import NavBar from '../components/NavBar.vue';
	import Footer from '../components/Footer.vue';

	export default {

		name: "AdminLayout",
		
		components: {
			MainSideBar,
			NavBar,
			Footer
		},
		data(){
			return {
				fileNameWithPath: ''
			}
		},
		computed: {
			authenticated() {
				return this.$store.state.auth.authenticated;
			},
		},
		created(){
			this.setBackground();
		},
		methods: {
			setBackground(){
				let files = import.meta.glob('../../../public/assets/img/login_bg/*.jpg');
				// let files = import.meta.globEager("../../../public/assets/img/login_bg/*.jpg");

				let file = this.pickRandomProperty(files);

				// image name
				var n = file.lastIndexOf('/');
				var imageNameWithExt = file.substring(n + 1);
				
				this.fileNameWithPath = '/assets/img/login_bg/' + imageNameWithExt;
			},
			pickRandomProperty(obj) {
    			var result;
				var count = 0;
				for (var prop in obj)
					if (Math.random() < 1/++count)
					result = prop;
				return result;
			}

		}
	}
</script>