import React, { Component } from 'react'

export default class Selection extends Component {

  addItems(){
    let temp =this.refs.item.value.split(':');
    let item =temp[0].trim(); 
    let price = temp[1].split(' ')[1].trim(); 
    let q  = this.refs.q.value.trim();
    if(q.trim() ===""){
      alert("How many "+item.trim()+"s?");
    }else{
      this.props.updateItemsFxn(item,q,price)
      this.refs.q.value = "";
    }
  }

  ejectPastries(){
    return this.props.allPastries.map((item,index)=>{
      return  <option key={index}>{item.name} : {item.price} ksh</option> 
    })
  }
  render() {
    return (
      <div>
        <div className="thumbnail cook-thumb clearfix" style={{padding: '50px'}}> 
          <h5 class="my-h5">Add all the food items to be shipped here</h5><br />
          <label>Choose Food Item</label>
          <select ref="item" className="form-control input-s" id="item-select"> 
           {this.ejectPastries()}
          </select>
          {/* <label> Unit Of Measurement</label>
          <select className="form-control" id="measurement-select"> 
            <option>Cups</option> 
            <option>Plates </option>
          </select> */}
          <label>How many?</label>
          <input ref="q" className="form-control input-s" type="number" id="quantity" placeholder="number" />
          <button onClick={()=>{this.addItems()}} className="little-margin btn btn-success float-right"><i className="fa fa-plus" /> Add </button>
        </div>
        
      </div>
    )
  }
}
