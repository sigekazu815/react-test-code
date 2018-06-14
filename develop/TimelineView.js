import React, { Component } from 'react';
import axios from 'axios';

export class TimelineView extends Component {

	constructor () {
		super();
		this.state = {
			tweets: null,
			tweetPlaces: []
		};
	}

	fetchTweet (url) {
		try {
			axios.get(url).then(res => {
				console.log('ツイート取得. status:', res.status);
				if (res.status >= 400) {
					return;
				} else {
					this.setState({tweets: res});
					console.log(this.state.tweets.data.statuses);
				}
			});
		} catch (e) {
			console.log('ツイート取得時エラー');
			console.log(e);
		}
	}

	componentDidMount () {
		//this.fetchTweet('http://192.168.1.9/php/tweetList.php');
		this.fetchTweet('http://192.168.1.9:3000/php/tweetList.php');
	}

	getRenderDOM () {
		try {
			if (!!this.state.tweets === false) {
				return '';
			} else {
				return this.state.tweets.data.statuses.map(val => {
					return (
						<div key={val.id} className="tweet">
							<div><span className="tweetProp">{val.user.name}</span></div>
							<div><span>{val.text}</span></div>
							<div><span className="tweetProp">{val.created_at}</span></div>
						</div>
					);
				});
			}
		} catch (e) {
			console.log(e);
			return (
				<div>解析時に不具合が起きた</div>
			);
		}
	}

	render () {
		return (
			<div className="timeline">{this.getRenderDOM()}</div>
		);
	}
}

export default TimelineView;
