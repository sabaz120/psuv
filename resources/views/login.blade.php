@extends('layouts.login')

@section('content')
    <div class="login_admin " id="dev-login">

        <div class="row">
            <div class="login100-more mask col-md-6"
                style="background-image: url('psuv-login.jpg');">


               <!---- <p>Bienvenido a Aidacaceres CMS</p>--->
            </div>
            <div class="login100-form validate-form col-md-6" v-cloak>

                <p class="text-center">
                    <img src="{{ url('psuv.png') }}" style="width: 250px;">
                </p>


                <div class="wrap-input100 validate-input" style="margin-top: 50px;">
                    <input class="input100" type="text" v-model="email">
                    <span class="focus-input100"></span>
                    <span class="label-input100">Correo electrónico</span>
                    
                </div>
                <small style="color: red; margin-top: 10px;" v-if="errors.hasOwnProperty('email')">@{{ errors['email'][0] }}</small>


                <div class="wrap-input100 validate-input">
                    <input class="input100" type="password" v-model="password">
                    <span class="focus-input100"></span>
                    <span class="label-input100">Contraseña</span>
                    
                </div>
                <small style="color: red; margin-top: 10px;" v-if="errors.hasOwnProperty('password')">@{{ errors['password'][0] }}</small>




                <div class="container-login100-form-btn">
                    <button class="login100-form-btn" @click="login()" style="background-color: #c0392b;" v-if="loading == false">
                        Ingresar
                    </button>
                    <p class="text-center">
                        <div class="loader-custom" v-if="loading"></div>
                    </p>
                </div>
                
            </div>


        </div>

    </div>
@endsection


@push("scripts")

<script type="text/javascript">
const app = new Vue({
    el: '#dev-login',
    data() {
        return {
            email: "",
            password: "",
            errors:[],
            loading:false
        }
    },
    methods: {

        login() {

            this.loading = true

            axios.post("{{ url('/login') }}", {
                    email: this.email,
                    password: this.password
                })
                .then(res => {
                 
                    this.loading = false
                    if (res.data.success == true) {

                        this.email = ""
                        this.password = ""

                        window.location.href = res.data.url


                    } else {
                        alert(res.data.msg)
                    }

                })
                .catch(err => {
                    this.loading = false
                    swal({
                        text:"Hay algunos campos que debes revisar",
                        icon: "error"
                    })

                    this.errors = err.response.data.errors

                })

        }

    },
    created() {


    }
});
</script>

@endpush