<template>
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <h5 class="nav-link">{{ this.$store.state.auth.user.company.name }}</h5>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="">
                <a href="#" class="nav-link" style="padding-right: 4px; padding-left: 1px;">
                    <button 
                        type="button" 
                        class="btn btn-xs btn-primary"
                        @click="showCurrentBalanceModal()"
                        :title="$t('balance') +' '+ $t('statement')"
                        >{{ $t('balance') }} {{ $t('statement') }}
                    </button>  
                </a>
            </li>
            <li class="">
                <a href="#" class="nav-link" style="padding-right: 0px; padding-left: 0px;">
                    <button 
                        type="button" 
                        class="btn btn-xs btn-info"
                        @click="$router.go(0)"
                        title="Reload"
                        ><i class="fas fa-sync-alt"></i>
                    </button>  
                </a>
            </li>
            <!-- user info -->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link" data-toggle="dropdown">
                    <span class="d-none d-md-inline">{{ this.$store.state.auth.user.first_name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-headset mr-2"></i> {{ $t('need_help') }}
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#"  @click="logout" class="dropdown-item">
                        <i class="fas fa-sign-out-alt mr-2"></i> {{ $t('logout') }}
                    </a>
                </div>
            </li>
            
            <!-- notice -->
            <!-- <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">15</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">15 Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> 4 new messages <span
                            class="float-right text-muted text-sm">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-users mr-2"></i> 8 friend requests <span
                            class="float-right text-muted text-sm">12 hours</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-file mr-2"></i> 3 new reports <span class="float-right text-muted text-sm">2
                            days</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
            </li> -->

            <!-- lang switcher-->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)" aria-expanded="true">
                    <i class="flag-icon" :class="$i18n.locale == 'en'?'flag-icon-us':'flag-icon-bd'"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right p-0">
                    <a href="javascript:void(0)" @click.prevent="updateLocale('en')" class="dropdown-item" :class="$i18n.locale == 'en'?'active':''">
                        <i class="flag-icon flag-icon-us mr-2"></i> English
                    </a>
                    <a href="javascript:void(0)" @click.prevent="updateLocale('bn')" class="dropdown-item" :class="$i18n.locale == 'bn'?'active':''">
                        <i class="flag-icon flag-icon-bd mr-2"></i> বাংলা
                    </a>
                </div>
            </li>

        </ul>
    </nav>

    <CurrentBalanceModal ref="cBRef"/>
    
</template>

<script>
import { mapActions } from 'vuex';
import CurrentBalanceModal from '../components/commons/CurrentBalanceModal.vue';

export default {
    components: {
		CurrentBalanceModal,
	},
    data() {
        return {
        }
    },
    created(){
        // console.log(this.$i18n.locale);
        // console.log(this.$store.state.auth.user)
        // console.log(this.$store.state.auth.user.company.payment_feature)
    },
    methods: {
        ...mapActions({
            signOut:"auth/logout"
        }),
        updateLocale(code){
            // this.$i18n.locale = code;/
            // this.$store.commit('updateCurrentLang', code);
            localStorage.setItem("ph_current_lang", code);
            // console.log('set = ' + code);
            // this.$router.go(this.$router.currentRoute);
            this.$router.go(0);  
        },
        async logout(){
            await axios.post('/logout').then(({data})=>{
                this.signOut();
                this.$router.go(0);
            })
        },
        // show modal
		showCurrentBalanceModal(){
			this.$refs.cBRef.getBalance();
			this.$refs.cBRef.getTransection();
			$("#showCurrentBalanceModal").modal("show");
		},
    }
}
</script>