<template>
	<div class="content-wrapper">

		<!-- <section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2"></div>
			</div>
		</section> -->

		<!-- <section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12"> -->
                        <MapboxMap
                            style="height: 850px"
                            :accessToken="token"
                            map-style="mapbox://styles/mapbox/streets-v11"
                            :center="[450.387823, 23.764327]"
                            :zoom="15">
                            <MapboxMarker :lng-lat="[450.387823, 23.764327]" />
                        </MapboxMap>
					<!-- </div>
				</div>
			</div>
		</section> -->


	</div>
</template>

<script>
import { MapboxMap, MapboxMarker  } from '@studiometa/vue-mapbox-gl';

export default {
    components: {
      MapboxMap,
      MapboxMarker
    },
    data(){
        return {
            token: 'pk.eyJ1IjoicGFyaWJhaGFuaGlzaGFiIiwiYSI6ImNsYndncmJnZjJxcDkzcXBqemd6eW1sa3QifQ.z3-sJK1lvIHXdeuhck1fqQ',

        }
    }
}
</script>