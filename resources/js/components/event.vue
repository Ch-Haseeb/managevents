<template>
  <div>
  
    <div class="container" style="margin-top:-80px;">
      <div class="form-group">
        <button
          class="btn btn-primary"
          @click.prevent="showModalOne = !showModalOne"
          id="btn"
        >
          Create Event
        </button>
      </div>

      <div class="customModal" v-if="showModalOne">
        <div class="customModalTitle">
          Event Form
        </div>
        <div class="customModalBody">
          <form @submit.prevent="submitForm">
            <legend><span class="number"></span>Event Details</legend>

            <label for="appointment_for">Event For*:</label>
            <textarea v-model="event.name"></textarea>
            <label for="date">Date*:</label>
            <input type="date" v-model="event.meeting_date" />
            <br />
            <label for="time">Time*:</label>
            <input type="time" v-model="event.meeting_time" />

            <br />
            <button type="submit" id="sub">Create Event</button>
          </form>
        </div>
        <div class="customModalFooter">
          <button
          id="cl"
            class="btn btn-primary"
            @click.prevent="showModalOne = !showModalOne"
          >
            Cancel
          </button>
        </div>
      </div>


    </div>


    <calandar />
  </div>
</template>

<script>
import axios from "axios";
import calandar from "./FullCalandar";
const token = localStorage.getItem('usertoken')

export default {
  components: { calandar },
  data: () => ({
    modalShow: false,
    text: "",
    showModalOne: false,
    showModalTwo: false,
    event: {
      name: "",
      meeting_date: "",
      meeting_time: "",
    },
  }),
  beforeMount(){
    // this.syncevent()
    if(this.$route.query.code){
    const a=this.$route.query.code
      axios.post('http://localhost:8000/api/syncevent', { id: a},
{ headers: { Accept: 'application/json', Authorization: `Bearer ${token}` } })
        .then((res) => {
          this.$router.push('/event')
        })
        .catch((err) => {
          console.log(err)
        });
      }
  },
  methods: {
    submitForm() {
      const form=this.event
      this.showModalOne=false,
      axios
        .post('http://localhost:8000/api/event', form, { headers: { Accept: 'application/json', Authorization: `Bearer ${token}` } })
        .then((res) => {
          console.log(res);
          location.reload(true)
        })
        .catch((err) => {
          console.log(err);
        });
    },
 
  },
};
</script>
<style scoped>
body {
  background-color: #5fcf80;
}
#body_header {
  width: auto;
  margin: 0px auto;
  text-align: center;
  font-size: 25px;
}
form {
  max-width: 300px;
  margin: 10px auto;
  padding: 10px 20px;
  background: #f4f7f8;
  border-radius: 8px;
}

h1 {
  margin: 0 0 30px 0;
  text-align: center;
}

input[type="text"],
input[type="password"],
input[type="date"],
input[type="datetime"],
input[type="email"],
input[type="number"],
input[type="search"],
input[type="tel"],
input[type="time"],
input[type="url"],
textarea,
select {
  background: rgba(255, 255, 255, 0.1);
  border: none;
  font-size: 16px;
  height: auto;
  margin: auto;
  outline: 0;
  padding: 8px;
  width: 100%;
  background-color: #e8eeef;
  color: black;
  box-shadow: 0 1px 0 rgba(0, 0, 0, 0.03) inset;
  margin-bottom: 30px;
}

input[type="radio"],
input[type="checkbox"] {
  margin: 0 4px 8px 0;
}

select {
  padding: 6px;
  height: 32px;
  border-radius: 2px;
}

#sub {
  padding: 19px 39px 18px 39px;
  color: #fff;
  background-color: #4bc970;
  font-size: 18px;
  text-align: center;
  font-style: normal;
  border-radius: 5px;
  width: 100%;
  border: 1px solid #3ac162;
  border-width: 1px 1px 3px;
  box-shadow: 0 -1px 0 rgba(255, 255, 255, 0.1) inset;
  margin-bottom: 10px;
}

fieldset {
  margin-bottom: 30px;
  border: none;
}

legend {
  font-size: 1.4em;
  margin-bottom: 10px;
}

label {
  display: block;
  margin-bottom: 8px;
}

label.light {
  font-weight: 300;
  display: inline;
}

.number {
  background-color: #5fcf80;
  color: #fff;
  height: 30px;
  width: 30px;
  display: inline-block;
  font-size: 0.8em;
  margin-right: 4px;
  line-height: 30px;
  text-align: center;
  text-shadow: 0 1px 0 rgba(255, 255, 255, 0.2);
  border-radius: 100%;
}

form {
  max-width: 480px;
}
.container {
  padding: 40px 80px 15px 80px;
  background-color: #fff;
  border-radius: 8px;
  text-align: center;
  max-width: 800px;
}
.heading {
  text-align: center;
}
.heading h1 {
  background: -webkit-linear-gradient(#fff, #999);
  -webkit-text-fill-color: transparent;
  -webkit-background-clip: text;
  text-align: center;
  margin: 0 0 5px 0;
  font-weight: 900;
  font-size: 4rem;
  color: #fff;
}
.heading h4 {
  color: lighten(#5c3d86, 30%);
  text-align: center;
  margin: 0 0 35px 0;
  font-weight: 400;
  font-size: 24px;
}
.btn {
  outline: none !important;
}

.form-group {
  margin-bottom: 25px;
}

.customModal {
  box-shadow: 0px 1px 12px rgba(0, 0, 0, 0.4);
  left: calc(50vw - 300px);
  position: absolute;
  z-index: 999;
  width: 600px;
  top: 20vh;
  border-radius: 5px;
  overflow: hidden;
}

.customModalTitle {
  background-color: #eee;
  text-align: left;
  padding: 8px 12px;
  font-size: 1.5em;
}
.customModalTitl .close {
  line-height: 32px;
}

.customModalBody {
  background-color: #fff;
  padding: 8px 12px;
  text-align: left;
  padding: 12px;
}
.customModalFooter {
  background-color: #eee;
  padding: 4px 12px;
  text-align: left;
}
#btn {
  background-color: #007bff;
    border-color: #007bff;
  border: none;
  color: white;
  padding: 10px 15px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  position: relative;
  display: block;
  left: 80%;
  top:  120px;
  cursor: pointer;
}
#cl{
  background-color: #4caf50; /* Green */
  border: none;
  color: white;
  padding: 10px 15px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  position: relative;
  display: block;
  cursor: pointer;
  background-color: #6c757d;
    border-color: #6c757d;

}
</style>