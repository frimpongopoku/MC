import React, { Component } from 'react';
import ReactDOM from 'react-dom';
export default class Accountant extends Component {

	constructor(props){
		super(props); 
		this.state = {
			accountant:null, 
			allShipments:null, 
			inFocus:null,
			editable:null,
			earnings:0,
			remainder:null

		}
	}
	getCompleteShipments(){
		var thisClass = this;
		$.ajax({method:'get',url:'/acc/get-orders'})
		.done(function(response){
			console.log(response);
			thisClass.setState({allShipments:response})
		})
	}
	getAcc(){
		var thisClass = this;
		$.ajax({method:'get',url:'/get/acc'})
		.done(function(response){
			thisClass.setState({accountant:response})
		})
	}
	componentDidMount() {
			this.getAcc()
			this.getCompleteShipments();
	}
	
	reconstruct(){
		var e = this.state.editable;
		var string=""; 
		e.names.forEach((item,index)=>{
			if(string ===""){
				string  = item+":"+e.prices[index]+":"+e.totals[index];
			}
			else{
				string  = string+","+item+":"+e.prices[index]+":"+e.totals[index];
			}
		})
		return string;
	}
	cleanUp(){
		this.setState({
			editable:null,
			inFocus:null,
			earnings:'',
			remainder:null
		});
	}
	deconstruct(description){//expects a string of compressed kitchen items like (cake:10:409,pie:43:4555)[item:price:price x howmany]
		let itemsArr = description.split(',');
		let names=[],prices=[],totals=[], amount=[]; 
		itemsArr.forEach((item,index) => {
			let arr  = item.split(':');
			names.push(arr[0]); 
			prices.push(arr[1]); 
			totals.push(arr[2]);
			amount.push(Number(arr[2])/Number(arr[1]))
	});
		return { names:names,prices:prices,totals:totals,amount:amount}
	}	
	ejectForReview(){
		if(this.state.allShipments !==null){
			var ship = this.state.allShipments; 
			if(ship.length !==0){
				return ship.map((item,index)=>{
					return (
						<div key={index} className="thumbnail raise-hover clearfix" style={{borderColor:'darkgoldenrod',cursor:'pointer',color:'white'}}>
							<button 
								onClick ={()=>{this.review(item)}}
							className="btn btn-success btn-sm float-right">Review</button>
							<small className=" text text-success"><b>
								Expecting: {item.expected_amount} KES</b></small>
							<h5>{item.title}</h5>
						</div>
					);
				})
			}
			else{
				return(
					<div className="thumbnail raise-hover" style={{borderColor:'darkgoldenrod',cursor:'pointer',color:'white'}}>
						<h3>No shipments have been completed yet!</h3>
					</div>
				);
			}
		}
	}
	ejectExpectedSituation(){
		if(this.state.inFocus !==null){
			return(
				<div className="thumbnail">
					<h5>Hi Mr Accountant, if the amount of money you have now matches the expected amount,<br/>
						please click the button below to finish<br/><br/>
						<button onClick = {()=>{this.showSpinner('spinner'); this.metExpectations()}}className="btn btn-primary">
						We Met Expectations
						<span id="spinner" style={{marginLeft:1,display:'none'}}><i class="fa fa-spinner fa-spin"></i></span>
						
						</button>
					</h5>
				</div>
			);
		}
	}
	ejectNames(){
		if(this.state.editable !==null){
			return this.state.editable.names.map((item,index)=>{
				return(
					<option key={index}>{item} - {this.state.editable.amount[index]}</option>
				)
			})
		}
	}
	reset(){
		var e = this.deconstruct(this.state.inFocus.description);
		this.refs.number.value = 0;
		this.setState({editable:e,earnings:0,remainder:null})
	}
	ejectLossSituation(){
		if(this.state.inFocus !==null){
			return(
				<div>
					<h5>If the amount you have does not match what is expected,<br/>
							please take sometime to indicate how many of which items were left over
						</h5>
						<hr/>
						<small>Item - ( quantity counted )</small>
					<select ref="item_name" className="form-control" style={{width:'50%'}}>
						{this.ejectNames()}
					</select>
					<small>How many remaining?</small>
						<form onSubmit={()=>{this.addLoss()}}>
							<input ref="number" type="number" placeholder="Eg. 3" className="form-control" style={{width:'30%'}} />
							<button 
								onClick ={(e)=>{e.preventDefault();this.addLoss()}}
							className="btn btn-success little-margin">Record</button>
							<button 
								onClick ={(e)=>{e.preventDefault();this.reset()}}
							className="btn btn-danger little-margin">Start Again</button>
						</form>
						<small className="text text-secondary">{this.stringForRemainders()} left unsold</small>
					<br/><br/>
					<h3 className="text text-danger">{this.state.earnings !==0?this.state.earnings.toString() + " KES ":'Final = '+this.state.inFocus.expected_amount}</h3>
					<button onClick={()=>{
						if(this.state.earnings !==0){
							this.showSpinner('spinner-b')
							this.didntMeetExpectations()
						}
						else{
							alert("If the amount you have equals "+this.state.inFocus.expected_amount+", please use the 'we met expectations button'");
						}
						}}className="btn btn-primary ">
						Finish
						<span id="spinner-b" style={{marginLeft:1,display:'none'}}><i class="fa fa-spinner fa-spin"></i></span>
						
						</button>
				</div>
			);
		}
		else{
			return(
				<center>
					<h3>You havent chosen any shipment for review yet!</h3>
				</center>
			);
		}
	}
	ejectSummary(){
		if(this.state.inFocus !==null){
			return(
				<div >
					<h4>{this.state.inFocus.title}</h4>
					<h3 className="text text-success">Expecting {this.state.inFocus.expected_amount} KES</h3>
				</div>
			);
		}
	}
	review(item){
		var dec = this.deconstruct(item.description);
		this.setState({inFocus:item,editable:dec}); 
	}
	getItemStream(name){//expects a string --which ( name of an item like, cake, or pie)
		var price,total,amount,i;
		var e  = this.state.editable;
		e.names.forEach((el,index)=>{
			if(name ===el){
				price = e.prices[index];
				total = e.totals[index];
				amount = e.amount[index];
				i = index;
			}
		})
		return {name:name,price:price,total:total,amount:amount,index:i};
	}
	sum(arr){//arr = array of numbers
    var val = 0; 
    arr.forEach(num => {
      val = val +Number(num);
    }); 
    return val;
  }
	putMeBackInTheSquad(train,index){
		var e = this.state.editable; 
		e.names.splice(index,1,train.name); 
		e.amount.splice(index,1,train.amount); 
		e.totals.splice(index,1,train.total);
		this.setState({editable:e,earnings:this.sum(e.totals)});
	}
	addLoss(){
		var name = this.refs.item_name.value.split('-')[0].trim(); 
		var how_many = Number(this.refs.number.value);
		if(how_many){
			var stream = this.getItemStream(name);
			var loss = how_many * Number(stream.price); 
			var new_amount = Number(stream.total) - loss;
			var new_quantity = Number(stream.amount) - how_many;
			var new_stream = {name:name,amount:new_quantity,total:new_amount}
			this.addToRemainderList(name,how_many);
			this.putMeBackInTheSquad(new_stream,stream.index)
		}
		else{
			alert("How many items were left? ")
		}
	}
	ifExistsIndex(item,arr){
    
    let index =arr.findIndex((a)=>{
      return a===item;
    }); 
    return index;
  }
	addToRemainderList(name,quantity){
		var names =[], qs=[],ind; 
		var rem = this.state.remainder; 
		if (rem !==null){//first time
			ind = this.ifExistsIndex(name,rem.names); 
			if( ind !==-1){ //item is already in there
				var new_rem = Number(rem.remainder[ind]) + Number(quantity); 
				rem.remainder.splice(ind,1,new_rem);
				this.setState({remainder:rem});
			}
			else{
				rem.names.push(name);
				rem.remainder.push(quantity);
				this.setState({ remainder:rem })
			}
		}
		else{
			names.push(name);
			qs.push(quantity);
			this.setState({ remainder:{names:names,remainder:qs} })
		}

	}
	stringForRemainders(){
		var string ="" ; 
		 if(this.state.remainder !==null){
			 var rem = this.state.remainder;
			 rem.names.forEach((item,i)=>{
				 var n = rem.remainder[i].toString();
				 	var s = n!=='1'?'s':'';
					if(string ===""){
						string = n +" "+item+s;
					}
					else{
						string = string+" ,"+n+" "+item+ s;
					}
			 })
		 }
		 return string;
	}
	didntMeetExpectations(){
		var thisClass = this;
		$.ajax({
			method:'get',
			url:'/didnt-meet/expectations',
			data:{
				id:this.state.inFocus.id,
				received_amount:this.state.earnings,
				received_description:this.reconstruct()
			}
		})
		.done(function(res){
			$('#spinner-b').hide();
			thisClass.getCompleteShipments();
			thisClass.cleanUp();
		})
	}
	showSpinner(spinner){
    document.getElementById(spinner).style.display  ="inline-block";
	}

	metExpectations(){
		var thisClass= this;
		$.ajax({
			method:'get',
			url:'/met/expectations',
			data:{
				id:this.state.inFocus.id,
			}
		})
		.done(function(res){
			$('#spinner').hide();
			thisClass.getCompleteShipments();
			thisClass.cleanUp();
		})
	}
  render() {
    return (
      <div>
				<div className="clearfix">
					<h1 style={{display:'inline-block',padding:15}}>{this.state.accountant !==null ? this.state.accountant.name : '...'}</h1>
					<div className="small-logout" onClick={()=>{window.location ="/accounting/logout"}}>
						<center>
							<i className="fa fa-sign-out" style={{fontSize:'25px'}}></i>
						</center>
					</div>
        </div>
				<div className="thumbnail" style={{background:'#0e4775',minHeight:300,maxHeight:400,overflowY:'scroll'}}>
					{this.ejectForReview()}
				</div>
					<div className="thumbnail">
						{this.ejectSummary()}
					<div>
						{this.ejectExpectedSituation()}
					</div>
					<div className="thumbnail clearfix">
					 {this.ejectLossSituation()}
					</div>
				</div>
      </div>
    )
  }
}

if(document.getElementById('react-acc-div')){
    ReactDOM.render(<Accountant />,document.getElementById('react-acc-div'));
}