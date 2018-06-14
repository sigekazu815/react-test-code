import React, { Component } from 'react';
import './App.css';
import TimelineView from'./TimelineView';


class App extends Component {
	constructor () {
		super();
		this.state = {
			gMap: null
		}
	}
	componentDidMount () {
		let mapCanvas = document.getElementById('map-canvas');
		let map = new window.google.maps.Map(mapCanvas, {
			center: new window.google.maps.LatLng(35.698548, 139.7071583),
			zoom: 15,
			minZoom: 5,
			maxZoom: 22,
			mapTypeId : window.google.maps.MapTypeId.ROADMAP,
			gestureHandling: "cooperative",
			disableDefaultUI: true,
		});
		this.setState({gMap: map});
		mapCanvas.style.height = '100vh';
	}
	render() {
		return (
			<div className="App">
				<header className="App-header">
					<span className="App-title">twitter ←→ Google Maps連携</span>
				</header>
				<div className="App-body">
					<TimelineView />
					<div id="map-canvas"></div>
				</div>
			</div>
		);
	}
}
export default App;
