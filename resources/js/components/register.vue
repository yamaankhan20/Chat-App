<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Register</div>
                    <div class="card-body">
                        <form @submit.prevent="submitform">
                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>
                                <div class="col-md-6">
                                    <input
                                        id="name"
                                        type="text"
                                        class="form-control"
                                        name="name"
                                        v-model="form.name"
                                        required
                                        autocomplete="name"
                                        autofocus
                                    />

                                    <span v-if="error.name" class="invalid-feedback" role="alert">
                                        <strong>{{ error.name }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">Email Address</label>

                                <div class="col-md-6">
                                    <input
                                        id="email"
                                        type="email"
                                        class="form-control"
                                        name="email"
                                        v-model="form.email"
                                        required
                                        autocomplete="email"
                                    />

                                    <span v-if="error.email" class="invalid-feedback" role="alert">
                                        <strong>{{ error.email }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>

                                <div class="col-md-6">
                                    <input
                                        id="password"
                                        type="password"
                                        class="form-control"
                                        name="password"
                                        v-model="form.password"
                                        required
                                        autocomplete="new-password"
                                    />

                                    <span v-if="error.password" class="invalid-feedback" role="alert">
                                        <strong>{{ error.password }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">Confirm Password</label>

                                <div class="col-md-6">
                                    <input
                                        id="password-confirm"
                                        type="password"
                                        class="form-control"
                                        name="password_confirmation"
                                        v-model="form.password_confirmation"
                                        required
                                        autocomplete="new-password"
                                    />
                                    <span v-if="error.confirmPassword" class="invalid-feedback" role="alert">
                                        <strong>{{ error.confirmPassword }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Register
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.invalid-feedback {
    display: block;
    color: red;
}
</style>
<script>
export default {
    data(){
        return {
            form:{
                name:'',
                email:'',
                password:'',
                password_confirmation:''
            },
            error:{}
        };
    },
    methods: {
         validation_password(){
            this.error={};
             let isValid = true;

             if (!this.form.name) {
                 this.error.name = "Name is required";
                 isValid = false;
             }
             if (!this.form.email) {
                 this.error.email = "Email is required";
                 isValid = false;
             }
             if (!this.form.password) {
                 this.error.password = "Password is required";
                 isValid = false;
             }
             if (this.form.password !== this.form.password_confirmation) {
                 this.error.password = "Passwords do not match";
                 this.error.password_confirmation = "Passwords do not match";
                 isValid = false;
             }

             return isValid;
         },
        async submitform(){
             if(this.validation_password()){
                 try{
                     const response = await axios.post("/register", this.form);
                     window.location.href = '/';
                     console.log("Registration successful:", response);
                 }catch (error){
                     if(error.response && error.response.status === 422){
                         this.error.email = error.response.data.errors.email[0];
                     }else{
                         console.error("unexpected error",error)
                     }

                 }
             }
        }
    }

}
</script>

