import React, { Component } from 'react'

export default class CenterView extends Component {

 
  ejectItems(){
    return this.props.items.map((item,index)=>{
      return (
        <div  key={index}className="thumbnail clearfix  added-item" style={{borderRadius: '10px', paddingLeft: '30px', paddingBottom: '0px'}}> 
          <button onClick={()=>{this.props.deleteFxn(index)}}className="btn btn-danger btn-sm float-right"><i className="fa fa-minus" /></button>
          <p>{item}  <span><b>{this.props.q[index]}</b></span></p>
       </div>
      );
    })
  }

  ejectShipments(){
    return this.props.shipments.map((item,index)=>{
      return <option key={index}>{item.id} : {item.title}</option>
    })
  }
  
  forwardMech(){
    if(this.props.shipments.length !==0){
      return (
        <div className="thumbnail center-thumb raise-hover clearfix"> 
        <h5>Items You have added </h5> 
        <div id="item-list">
            {this.ejectItems()}
        </div>
        <hr /> 
        <label>Which shipment did you count the above items from?</label>
        <select className="form-control" ref="destination"> 
          {this.ejectShipments()}
        </select> 
        <label className="text text-info" style={{fontWeight:700}}>Please review all items before submitting </label>
        <button data-toggle="modal" onClick={()=>{this.props.destination(this.refs.destination.value)}}data-target="#confirmation-modal" className="btn btn-primary float-right little-margin">Send Shipment</button>
      </div>
      );
    }
    else{
      return (<center>
          <h3>No items have been shipped to this center yet.<br/> Please contact the kitchen staff to ship. <br/>
            Enjoy your free time
          </h3>
      </center>)
    }
  }
  render() {
    return (
      <div>
        {this.forwardMech()}
      </div>
    )
  }
}
