<template>
    <div class="login-page">
     

      <div class="container">
         <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-8 mx-auto">
                  <h1>Sign In</h1>
                  <form @submit.prevent="loginForm">
                     <input v-model="email" type="email" class="form-control" placeholder="Email" required>
                     <br />
                     <input v-model="password" type="password" class="form-control" placeholder="Password" required>
                     <br />
                     <input type="submit" class="btn btn-primary" >
                     <p>Don't have an account?  <router-link to="/register"> Sign up here</router-link>
                     </p>
                    
                  </form>

                 
            </div>
         </div>

      </div>
   </div>
</template>


<script>
import axios from "axios";
    export default {
        data() {
            return {
               email: '',
               password: '',

            }
        },
        methods: {
         loginForm(){
            axios
        .post("http://localhost:8000/api/login", {
         email:this.email,
         password:this.password,
        })
        .then((res) => {
         localStorage.setItem('usertoken', res.data.token)

         this.$router.push('/event')
     
          this.email = " ";
          this.password= " ";
        })
        .catch((err) => {
          console.log(err);
        });
         }
         
        }
    }
</script>
<style >
p {
   line-height: 1rem;
}

.card {
   padding: 20px;
}

.form-group 
   input {
      margin-bottom: 20px;
   }


.login-page {
   align-items: center;
   display: flex;
   height: 100vh;
}
   
   .fade-enter-active,
   .fade-leave-active {
  transition: opacity .5s;
}
   .fade-enter,
   .fade-leave-to {
      opacity: 0;
   }
   

   h1 {
      margin-bottom: 1.5rem;
   }


.error {
   animation-name: errorShake;
   animation-duration: 0.3s;
}

@keyframes errorShake {
   0% {
      transform: translateX(-25px);
   }
   25% {
      transform: translateX(25px);
   }
   50% {
      transform: translateX(-25px);
   }
   75% {
      transform: translateX(25px);
   }
   100% {
      transform: translateX(0);
   }
}

</style>