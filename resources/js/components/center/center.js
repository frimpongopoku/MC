import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import FoodSelection from './center-selection'; 
import ItemView from './center-view';

export default class Center extends Component {
  constructor (props){
    super(props);
    this.delItem = this.delItem.bind(this);
    this.setDestination = this.setDestination.bind(this);
    this.updateItems = this.updateItems.bind(this);
    this.state ={ shipments:[],allPastries:[],items:[], quantity:[],prices:[],destination:null, allCenters:[]};
  }


  delItem(index){
    let items = this.state.items.filter(function(value,i){
      return i !== index;
    })
    let quantity = this.state.quantity.filter(function(value,i){
      return i !== index;
    })
    let prices = this.state.prices.filter(function(value,i){
      return i !== index;
    })
    this.setState({items:items,quantity:quantity,prices});
  }
  ifExistsIndex(item){
    let index = this.state.items.findIndex((a)=>{
      return a===item;
    }); 
    return index;
  }

  replacement(index,item,quantity,price){
    let items = this.state.items; 
    let numbers = this.state.quantity; 
    let prices = this.state.prices;
    items.splice(index,1,item); 
    numbers.splice(index,1,quantity); 
    prices.splice(index,1,price); 
    this.setState({items:items,quantity:numbers,prices:prices});
  }
  newInsertion(item,quantity,price){
    let items = this.state.items; 
    let numbers = this.state.quantity; 
    let prices = this.state.prices;
    items =[...items,item];
    numbers = [...numbers, quantity]; 
    prices = [...prices, price]; 
    this.setState({items:items, quantity:numbers, prices:prices});
  }
  updateItems(item,quantity,price){
    let non_existent = -1;
    let index = this.ifExistsIndex(item);
    if(index !== non_existent){
      this.replacement(index,item,quantity,price);
    } 
    else{
      this.newInsertion(item,quantity,price);
    }
  }

  showSpinner(){
    document.getElementById('spinner').style.display  ="inline-block";
  }
  sendButton(){
    if(this.state.items.length !==0){
      return <button onClick = {()=>{this.showSpinner();this.shipValues()}}className="btn btn-secondary">
      Send
        <span id="spinner" style={{marginLeft:1,display:'none'}}><i class="fa fa-spinner fa-spin"></i></span>
      </button>
    }
  }
  setDestination(where){
    this.setState({destination:where})
  }
  ejectBasketItems(){
    if(this.state.items.length !==0){
      return this.state.items.map((item,index)=>{
        return (
          <h3 key={index}>{item}  <span className="text text-success">{this.state.quantity[index]}</span> </h3>
        );
      })
    }
    else{
      return ( <h3>You have no yet counted any items</h3>);
    }
  }

  prepareForDatabase(){
    let temp = ""; 
    this.state.items.forEach((item,index)=>{
      if(temp ===""){
        temp = item+':'+this.state.quantity[index]+':'+this.state.prices[index]; 
      }
      else{
        temp = temp+"<==>"+item+':'+this.state.quantity[index]+':'+this.state.prices[index]; 
      }
    })
    return temp;
  }
  shipValues(){
    $.ajax({
      method:'get',
      url:'/receive-values-from/center',
      data:{
        description:this.prepareForDatabase(),
        title:this.state.destination 
      }
    })
    .done(function(){
      window.location = "/centers/home";
    })
  }

  getAvailableShipments(){
    let thisClass = this;
    $.ajax({url:'/get/center/shipments',method:'get'})
    .done(function(response){
      thisClass.setState({shipments:response})
    });
  }
  getCenters(){
    let thisClass = this;
    $.ajax({url:'/get/centers',method:'get'})
    .done(function(response){
      thisClass.setState({allCenters:response})
    })
  }
  getPastries(){
    let thisClass = this;
    $.ajax({url:'/get/pastries',method:'get'})
    .done(function(response){
      thisClass.setState({allPastries:response})
    })
  }
  componentDidMount() {
    this.getCenters();
    this.getPastries();
    this.getAvailableShipments();
  }
  

    render() {
        return (
        <div>
          <FoodSelection allPastries={this.state.allPastries}updateItemsFxn ={ this.updateItems } />
          <ItemView  shipments = {this.state.shipments} destination={this.setDestination} deleteFxn ={this.delItem} items={this.state.items} q ={this.state.quantity} prices ={this.state.prices}/>
          <div className="modal fade" id="confirmation-modal"> 
            <div className="modal-dialog modal-md">
              <div className="modal-content">
                <div className="modal-header clearfix">
                <h4 className="modal-title">Confirm Values</h4>
                  <h1 className="close" data-dismiss="modal" >
                  <i class="fa fa-close"></i>
                  </h1>
                </div>
                <div className="modal-body" style={{maxHeight:'250px',overflowY:'scroll'}}>
                  <p>Final Values you have counted for <span class="text text-success" style={{fontWeight:700}}>"{this.state.destination}"</span> </p>
                   {this.ejectBasketItems()} 
                </div>
                <div className="modal-footer">
                  <button className="btn btn-danger" data-dismiss="modal">Cancel</button>
                  {this.sendButton()}
                </div>
              </div>
            </div>
          </div>
        </div>
        );
    }
}
if (document.getElementById('center-react-div')) {
    ReactDOM.render(<Center />, document.getElementById('center-react-div'));
}
