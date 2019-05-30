import React, { Component } from 'react'

export default class View extends Component {

 
  ejectItems(){
    return this.props.items.map((item,index)=>{
      return (
        <div  key={index}className="thumbnail clearfix  added-item" style={{ paddingLeft: '30px', paddingBottom: '0px'}}> 
          <button onClick={()=>{this.props.deleteFxn(index)}}className="btn btn-danger btn-sm float-right"><i className="fa fa-minus" /></button>
          <p>{item}  <span><b>{this.props.q[index]}</b></span></p>
       </div>
      );
    })
  }

  ejectCenters(){
    return this.props.allCenters.map((item,index)=>{
      return <option key={index}> {item.name}</option>
    })
  }
  render() {
    return (
      <div>
        <div className="thumbnail  cook-thumb clearfix" style={{padding: '30px'}}> 
            <h5 class="">Items You have added </h5> 
            <div id="item-list">
                {this.ejectItems()}
            </div>
            <hr /> 
            <label>Which center are you taking this shipment to?</label>
            <select className="form-control "ref="destination"> 
              {this.ejectCenters()}
            </select> 
            <label className="text text-info" style={{fontWeight:700}}>Please review all items before submitting </label>
            <button data-toggle="modal" onClick={()=>{this.props.destination(this.refs.destination.value)}}data-target="#confirmation-modal" className="btn btn-primary float-right little-margin">Send Shipment</button>
          </div>
      </div>
    )
  }
}
