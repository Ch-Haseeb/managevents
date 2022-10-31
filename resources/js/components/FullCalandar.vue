<script>
import axios from "axios";
const token = localStorage.getItem('usertoken')
import '@fullcalendar/core/vdom' // solves problem with Vite
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'

export default {
  components: {
    FullCalendar // make the <FullCalendar> tag available
  },
  data() {
    return {
      calendarOptions: {
        plugins: [ dayGridPlugin, interactionPlugin ],
        initialView: 'dayGridMonth',
        dateClick: this.handleDateClick,
        events: [],
      },
    }
  },
  beforeMount(){
    this.getallevent();
  },
  methods: {
    handleDateClick: function(arg) {
      alert('date click! ' + arg.dateStr)
    },
    getevent(){
      axios
        .get('http://localhost:8000/api/calandar', { headers: { Accept: 'application/json', Authorization: `Bearer ${token}` } })
        .then((res) => {
          const url=res.data.data
          window.open(url, '_blank', 'noreferrer');

        
        })
        .catch((err) => {
          console.log(err);
        });

    },
    getallevent(){
      axios.get('http://localhost:8000/api/getallevent', { headers: { Accept: 'application/json', Authorization: `Bearer ${token}` } })
      .then((res) => {
          const result=res.data.data
          this.calendarOptions.events = result.map((event) => ({
            title: event.name,
            date: event.date,
          }));
        })
        .catch((err) => {
          console.log(err);
        });
    },
  }
}
</script>
<template>

  <div>
    {{hello}}
    <button  class="button"  @click="getevent">
    <font-awesome-icon icon="rotate" />
    Sync Event
  </button>
  <FullCalendar :options="calendarOptions"  id="calendar"/>
  </div>
</template>
<style>
.button {
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
  left: 77%;
  top:40px;
  cursor: pointer;
}
#calendar a{
  color: #000000;
  text-decoration: none;

}
</style>