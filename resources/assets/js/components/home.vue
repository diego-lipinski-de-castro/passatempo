<template>
	<div>
		<loading :active.sync="loading"></loading>

		<div class="m-5">
			<div class="row justify-content-center">
				<div class="col-md-12">
					<div class="card">

						<div class="card-header"> Home </div>

						<div class="card-body">
							<div class="k-form p-2">
								<div class="form-group">
									<input class="form-control" v-model="k" type="number" placeholder="K">
								</div>

								<div class="form-group">
									<input class="form-control" v-model="it" type="number" placeholder="It">
								</div>

								<button :disabled="loading" @click="calc" class="btn btn-primary w-100"> Calc </button>
							</div>

							<hr>

							<line-chart :data="lineData"></line-chart>
							<hr>
							<line-chart :data="lineDataK"></line-chart>
							<hr>
							<hr>

							<scatter-chart :data="scatterData" xtitle="Total" ytitle="Orders"></scatter-chart>
							<hr>
							<scatter-chart :data="scatterDataK" xtitle="Total" ytitle="Orders"></scatter-chart>
							<hr>
							<hr>

							<scatter-chart :data="scatterDataInverted" xtitle="Total" ytitle="Orders"></scatter-chart>
							<hr>
							<scatter-chart :data="scatterDataInvertedK" xtitle="Total" ytitle="Orders"></scatter-chart>
							<hr>
							<hr>

							<geo-chart :data="geoData"></geo-chart>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>

    import Loading from 'vue-loading-overlay'
    import 'vue-loading-overlay/dist/vue-loading.min.css'

    export default {
    name: 'home',
    components: {
        Loading
    },
    data: () => ({
    loading: false,
    k: 3,
    it: 500,
    lineData: [],
    scatterData: {},
    scatterDataInverted: {},
    geoData: [],
    lineDataK: [],
    scatterDataK: {},
    scatterDataInvertedK: {},
    geoDataK: [],
    }),
    methods: {
    getData() {
        this.loading = true
        this.$axios.get('/api/users-orders')
        .then(res => {

        // const data = res.data

        // this.lineData = Object.keys(data.line).map(country => {
        //     return {
        //         name: country,
        //         data: _.zipObject(
        //         data.line[country].map(user => user.orders_count),
        //         data.line[country].map(user => user.total_waste),
        //         )
        //     }
        // })

        // this.scatterData = data.scatter.map(user => {
        // return [user.total_waste, user.orders_count]
        // })

        // this.scatterDataInverted = data.scatter.map(user => {
        // return [user.orders_count, user.total_waste]
        // })

        // this.geoData = data.geo.map(country => {
        // return [country.name, country.orders_count]
        // })

        // this.calc()

        this.loading = false

        })
        .catch(error => {
        console.error(error)
        this.loading = false
        })
    },

    calc() {
        this.$k.k(this.k)
        this.$k.iterations(this.it)

        this.$k.data(this.scatterData)
        this.scatterDataK = this.$k.clusters().map(cluster => {
            return cluster.centroid
        })

        this.$k.data(this.scatterDataInverted)
        this.scatterDataInvertedK = this.$k.clusters().map(cluster => {
            return cluster.centroid
        })

        this.lineDataK = this.lineData.map(country => {
        	return {
        		name: country.name,
        		data: Object.keys(country.data).map((key, index) => {
        			return [parseInt(key), country.data[key]]
        		})
        	}
        })

        console.log(this.lineDataK)

    }
    },
    mounted() {
    this.getData()
    }
    }

</script>

<style>
    .k-form {
        border: 1px solid rgba(0, 0, 0, 0.125);
        border-radius: 0.25rem;
        z-index: 9999;
        background-color: rgb(240, 240, 240);
        position: fixed;
        top: 10px;
        right: 20px;
    }
</style>
