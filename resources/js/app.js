import './bootstrap';

window.Echo.channel("first-event").listen("FirstEvent", (e) => {
    console.log(e);
});
