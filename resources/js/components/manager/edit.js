import React, { Component } from 'react'

export default class Edit extends Component {
  
  constructor(props){
    super(props); 
    this.state = {pastries:[]}
  }

  ejectCenterItems(){
    let items = this.props.editable; 
    if(items !==null){
      return items.center.names.map((item,index)=>{
        return (
          <div key={index} className="thumbnail added-item clearfix">
            <button onClick = {()=>{this.props.delete('center',index)}} className="btn little-margin btn-secondary btn-sm float-right">
              <i className="fa fa-minus"></i>
            </button>
            <small>{item} {items.center.amount[index]}</small>
          </div>
        );
      })
    }
  }
  ejectKitchenItems(){
    let items = this.props.editable; 
    if(items !==null){
      return items.kitchen.names.map((item,index)=>{
        return (
          <div key={index} className="thumbnail added-item clearfix">
            <button onClick = {()=>{this.props.delete('kitchen',index)}}className="btn little-margin btn-secondary btn-sm float-right">
              <i className="fa fa-minus"></i>
            </button>
            <small>{item} {items.kitchen.amount[index]}</small>
          </div>
        );
      })
    }
  }
  componentDidMount() {
    this.getPastries();
  }
  
  ejectPastries(){
    return this.state.pastries.map((item,index)=>{
      return <option key={index}>{item.name} : {item.price} ksh</option>
    })
  }
  getPastries(){
    let thisClass = this;
    $.ajax({method:'get',url:'/get/pastries'})
    .done(function(res){
      thisClass.setState({pastries:res})
    })
  }
  ifExistsIndex(item,arr){
    
    let index =arr.findIndex((a)=>{
      return a===item;
    }); 
    return index;
  }

  createObjFor(which,name,price,amount,index){
    var names,prices,numbers,wholeBasket;
    if(which ==='kitchen'){
       wholeBasket = this.props.editable.kitchen;
    }
    else{
      wholeBasket= this.props.editable.center;
    }
    if(index ===-1){
      names =[...wholeBasket.names,name]; 
      prices = [...wholeBasket.prices,price]
      numbers = [...wholeBasket.amount,amount]
      return {names:names,prices:prices,amount:numbers}
    }else{
      names =wholeBasket.names; 
      prices = wholeBasket.prices;
      numbers =wholeBasket.amount;
      names.splice(index,1,name); 
      prices.splice(index,1,price);
      numbers.splice(index,1,amount);
      return {names:names,prices:prices,amount:numbers}
    }
  }
  addToKitchen(){
    let selection = this.refs.pastry.value; 
    let name = selection.split(':')[0].trim(); 
    let price = selection.split(':')[1].trim().split(" ")[0]; 
    let amount = this.refs.number.value;
    let index = this.ifExistsIndex(name,this.props.editable.kitchen.names);
    let newKitchen = this.createObjFor('kitchen',name,price,amount,index)
    let newEditable = {kitchen:newKitchen,center:{...this.props.editable.center}};
    this.props.streamEdits(newEditable);
  }
  addToCenter(){
    let selection = this.refs.center_pastry.value; 
    let name = selection.split(':')[0].trim(); 
    let price = selection.split(':')[1].trim().split(" ")[0]; 
    let amount = this.refs.center_number.value;
    let index = this.ifExistsIndex(name,this.props.editable.center.names);
    let newCenter = this.createObjFor('center',name,price,amount,index)
    let newEditable = {center:newCenter,kitchen:{...this.props.editable.kitchen}};
    console.log(newCenter)
    this.props.streamEdits(newEditable);
  }
  render() {
    return (
      <div>
        {/* <div className="thumbnail" > 
          <p>All the items that were sent from the kitchen</p>
          <div id="add-pane">
            <select className="form-control" ref="pastry">
              {this.ejectPastries()}
            </select>
            <input ref="number" type="number" placeholder="how many"  className="form-control" style={{width:'27%',display:'inline-block'}}/>
            <button  onClick ={()=>{this.addToKitchen()}}className="btn btn-success little-margin">Add</button>
          </div>
          <div style={{height:250,maxHeight:250,minHeight:250,overflowY:'scroll'}}>
            {this.ejectKitchenItems()}
          </div>
        </div> */}
        <div className="thumbnail" > 
          <div id="add-pane">
          <p>All the items that were sent from the center</p>
          <select className="form-control" ref="center_pastry">
            {this.ejectPastries()}
          </select>
              <input ref="center_number" type="number" placeholder="how many?"  className="form-control" style={{width:'27%',display:'inline-block'}}/>
              <button  onClick ={()=>{this.addToCenter()}}className="btn btn-success little-margin">Add</button>
            <div style={{height:250,maxHeight:250,minHeight:250,overflowY:'scroll'}}>
              {this.ejectCenterItems()}
            </div>
          </div>
        </div>
      </div>
    )
  }
}
