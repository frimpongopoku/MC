import React, { Component } from 'react'
import ReactDOM from 'react-dom';
import Edit from './edit';

export default class Manager extends Component {

  constructor(props){
    super(props); 
    this.streamEdits = this.streamEdits.bind(this);
    this.deleteFrom = this.deleteFrom.bind(this);
    this.state = {
      manager:null,
      itemInFocusID:null,
      availableForReview:[],
      inFocus:null,
      focusPairs:[],
      editable:null,
  
    };
  }
  componentDidMount() {
    this.getManagerShipment();
    this.getManager();
  }
 
  deconstructDesc(description){
    let itmMash = description.split('<==>'); 
    let nameArr = []; 
    let amountArr = []; 
    let priceArr = []; //for one of eachitem
    itmMash.forEach(item => {
        let pieces = item.split(':'); 
        nameArr.push(pieces[0]);
        amountArr.push(pieces[1]); 
        priceArr.push(pieces[2]);
    }); 
    return { names: nameArr, amount:amountArr,prices:priceArr}
  }
  getManagerShipment(){
    let thisClass = this;
    $.ajax({method:'get',url:'/get/shipment-for-management'})
    .done(function(response){
      thisClass.setState({availableForReview:response});
    })
  }
  genPairs(kitchen,center){
    let pairArr = [];
      kitchen.names.forEach((item,index)=>{
        let k = {name:item,amount:kitchen.amount[index],single_price:kitchen.prices[index]} 
        var l ={};
        var numbers_match = false;
        center.names.forEach((c,id)=>{
            if(c.trim() === item.trim()){
               l = {name:c,amount:center.amount[id],single_price:center.prices[id]}
                if(Number(kitchen.amount[index]) === Number(center.amount[id])){
                  numbers_match = true;
                }
              }
            
         })
         pairArr.push({k:k,c:l,numbers_match:numbers_match});
      })
    return pairArr;
  }
  fashionForFocus(dbArray){ 
    let kitchen = this.deconstructDesc(dbArray.kitchen_shipment.description); 
    let center = this.deconstructDesc(dbArray.center_shipment.description); 
    return {kitchen:kitchen, center:center};
  }
  ejectForReview(){
    return this.state.availableForReview.map((item,index)=>{
      return(
        <div  key={index} className="thumbnail clearfix" style={{cursor:'pointer',padding:20,paddingBottom:5,background:'antiquewhite'}}> 
          <button onClick={()=>{
            var f = this.fashionForFocus(item);
            this.setState({
              itemInFocusID:item.id,
              inFocus:f,
              focusPairs:this.genPairs(f.kitchen,f.center),
            })
          }}
          className="btn btn-primary float-right btn-sm">Review</button>
          <p>{item.title}</p>
        </div>
      )
    })
  }
  emptyReviewList(){
    if(this.state.availableForReview.length ===0){
      return(
        <div  className="thumbnail clearfix" style={{cursor:'pointer',padding:20,paddingBottom:5,background:'antiquewhite'}}> 
          <p>Great news! No mistakes for you to correct yet!</p>
        </div>
      )
    }
  }
  showSpinner(id){
    document.getElementById(id).style.display  ="inline-block";
  }
  fashionForEdit(kitchen,center){
    let k_string = ""; 
    let c_string = "";
    let w_kitchen = kitchen; 
    let w_center = center; 
    w_kitchen.names.forEach( (item,index)=>{
        if(k_string ===""){
          k_string = item+":"+w_kitchen.amount[index]+":"+w_kitchen.prices[index]; 
      }else{
        k_string = k_string+","+item+":"+w_kitchen.amount[index]+":"+w_kitchen.prices[index];
      }
    })
    w_center.names.forEach( (item,index)=>{
      if(c_string ===""){
          c_string = item+":"+w_center.amount[index]+":"+w_center.prices[index]; 
      }else{
        c_string = c_string+","+item+":"+w_center.amount[index]+":"+w_center.prices[index];
      }
    })
   return  { k:k_string, c:c_string}
  }
  listComparison(){

    if(this.state.inFocus !==null){
      return this.state.focusPairs.map((item,index)=>{
        if(item.numbers_match){
          return(
            <div key={index} className="comparison clearfix">
              <div className="thumbnail" style={{display:'inline-block',paddingBottom:5}}>
                <h4>{item.k.name}  <span className="text text-success">{item.k.amount}</span></h4>
              </div>
              <div style={{border:'solid 2px black',width:"20vh",display:'inline-block'}}></div>
             <div className="thumbnail" style={{display:'inline-block',paddingBottom:5}}>
                <h4>{item.c.name?item.c.name:'NA'}  <span className="text text-success">{item.c.amount?item.c.amount:'NA'}</span></h4>
              </div>
              <div className="inline float-right" > 
                <h1 style={{position:'absolute',right:'20%',marginTop:10}}><i className="fa fa-check-circle text text-success"></i></h1>
              </div>
            </div>
          );
        }
        else{
          return(
            <div key={index} className="comparison clearfix">
              <div className="thumbnail" style={{display:'inline-block',paddingBottom:5}}>
                <h4>{item.k.name}  <span className="text text-danger">{item.k.amount}</span></h4>
              </div>
              <div style={{border:'solid 2px black',width:"20vh",display:'inline-block'}}></div>
              <div className="thumbnail" style={{display:'inline-block',paddingBottom:5}}>
                <h4>{item.c.name ?item.c.name:'NA'}  <span className="text text-danger">{item.c.amount?item.c.amount:'NA'}</span></h4>
              </div>
              <div className="inline float-right" > 
                <h1 style={{position:'absolute',right:'20%',marginTop:10}}><i className="fa fa-times-circle text text-danger"></i></h1>
              </div>
            </div>
          );
        }
      });
    }else{
      return <p>You havent chosen anyting to review yet!</p>
    }
  }

  compressArraysForDb(obj){ //expects an obj of { kitchen:[names][prices][amounts],center:[names][amounts][prices]}
    let k = obj.kitchen; 
    let c = obj.center ; 
    let k_s = ""; 
    let c_s = "";
    k.names.forEach((item,index)=>{
      if(k_s ===""){
        k_s = item+":"+k.amount[index]+":"+k.prices[index]; 
      }
      else{
        k_s = k_s+"<==>"+item+":"+k.amount[index]+":"+k.prices[index]
      }
    }); 
    c.names.forEach((item,index)=>{
      if(c_s ===""){
        c_s = item+":"+c.amount[index]+":"+c.prices[index]; 
      }
      else{
        c_s = c_s+"<==>"+item+":"+c.amount[index]+":"+c.prices[index]
      }
    }); 

    return {kitchenString:k_s, centerString:c_s};
  }
 

  submitEdits(){
    
    if(this.state.editable){
      this.showSpinner('b-spinner');
      let set = this.compressArraysForDb(this.state.editable);
      $.ajax({
        method:'get',
        url:'/manager/rectify',
        data:{
          id:this.state.itemInFocusID,
          kitchen_description:set.kitchenString,
          center_description:set.centerString
        }
      })
      .done(function(response){
        window.location = "/centers/manager/home";
      })
    }
    else{
      alert("You have not chosen anything!")
    }
  }
  sendToAccountant(values){
    var thisClass = this;
    $.ajax({
      method:'get',
      url:'/manager/forward-to-acc', 
      data:{
        desc:values.item_string,
        expected_amount:values.expected_amount, 
        not_id:thisClass.state.itemInFocusID
      }
    })
    .done(function(res){
      thisClass.getManagerShipment();
      thisClass.setState({inFocus:null});
      $('#spinner').hide();
    })
  }
  verify(){
    var status = true;
    if(this.state.inFocus !==null){
      var p = this.state.focusPairs;
      for(var i=0; i < p.length; i++){
        if(!p[i].numbers_match){
         status = false;
          break;
        }
      }
      if(status){
        var values = this.mapItemToExpectedPrice();
        this.showSpinner('spinner');
        this.sendToAccountant(values);
      }
      else{
        alert("You cannot seal this shipment, there are discrepancies");
      }
    }
    else{
      alert("No shipment is under review yet!")
    }
  }
   
  deleteFrom(where,index){
    var names,prices,amounts;
    if(where ==="kitchen"){
      names = this.state.editable.kitchen.names.filter((item,i)=>{
        return i !==index;
      });
      prices = this.state.editable.kitchen.prices.filter((item,i)=>{
        return i !==index;
      });
      amounts = this.state.editable.kitchen.amount.filter((item,i)=>{
        return i !==index;
      });
      var kitchen = {names:names,prices:prices,amount:amounts};
      var center  = {...this.state.editable.center};
      this.setState({editable:{kitchen:kitchen,center:center}});
    }else{
      names = this.state.editable.center.names.filter((item,i)=>{
        return i !==index;
      });
      prices = this.state.editable.center.prices.filter((item,i)=>{
       return i !==index;
      });
      amounts = this.state.editable.center.amount.filter((item,i)=>{
        return i !==index;
      });
      var center = {names:names,prices:prices,amount:amounts};
      var kitchen = {...this.state.editable.kitchen}
      this.setState({editable:{kitchen:kitchen,center:center}});
    }


  }
  streamEdits(newEditable){
    this.setState({editable:newEditable})
  }
  
  sum(arr){//arr = array of numbers
    var val = 0; 
    arr.forEach(num=>{
      val = val +Number(num);
    }); 
    return val;
  }
  itemToTotal(arr){//expects arr = {names[],prices[],items[]}
    var names = [];
    var totals =[];
    arr.names.forEach((item,index)=>{
      let amount = Number(arr.amount[index]); 
      let price = Number(arr.prices[index]); 
      let total = amount * price;
      names.push(item); 
      totals.push(total);
    })
    return {names:names,prices:arr.prices,totals:totals,expected:this.sum(totals)}; 
  }
  shrinkItemsWithTotals(obj){ //obj  = {names:[],totals:[total amount of money expected from each item]}
    var string = ""; 
    obj.names.forEach((item,index)=>{
      if(string ===""){
        string = item+":"+obj.prices[index]+":"+obj.totals[index].toString();
      }
      else{
        string = string+','+item+":"+obj.prices[index]+":"+obj.totals[index].toString();
      }
    })
    return string;
  }
  mapItemToExpectedPrice(){ //returns a compressed string from 'inFocus'
    var ready = this.state.inFocus; 
    var toTotal =this.itemToTotal(ready.kitchen);
    var expectedValues = this.shrinkItemsWithTotals({names:toTotal.names,prices:toTotal.prices,totals:toTotal.totals});
    return {item_string:expectedValues,expected_amount:toTotal.expected};
  }
  getManager(){
    var thisClass = this;
    $.ajax({method:'get',url:'/get/manager'})
    .done(function(res){
      thisClass.setState({manager:res})
    })
  }
  
  sealAfterEdit(){
    if(this.state.editable){
      this.showSpinner('b-spinner');
      var a = this.itemToTotal(this.state.editable.center); 
      var obj = {names:a.names, prices:a.prices,totals:a.totals};
      var valuesInString  = this.shrinkItemsWithTotals(obj);
      var not_id = this.state.itemInFocusID;
      $.ajax({
        method:'get',
        url:'/manager/forward-anyway', 
        data:{
          desc : valuesInString,
          expected_amount: a.expected, 
          not_id: not_id
        }
      })
      .done(function(){
        window.location = "/centers/manager/home";
      });
    }
    else{
      alert("You have to select a shipment first")
    }
    
  }
  render() {
    return ( 
      <div>
        <h3 style={{padding:17}}>The items in the list below will help you compare the number of food stuff that were counted by the kitchen staff, and the values counted in your center</h3>
        <div style={{padding:"1px 17px"}}>
         <p style={{display:'inline-block'}}><b>{this.state.manager !==null?this.state.manager.name:'...'} </b> 
          you manage all shipments from 
          <span style={{marginLeft:3,border:'solid 2px #ccc', padding:'5px 15px',borderRadius:55}}>
          {this.state.manager !==null ? this.state.manager.center.name: '...'}
          </span>
          </p>
          <h5 onClick={()=>{window.location = "/management/logout"}}style={{cursor:'pointer',border:'solid 2px #ccc',padding:"10px 13px",margin:6,borderRadius:"100%",textAlign:'center',display:'inline-block'}}><span className="fa fa-sign-out"></span></h5>
        </div>
        <div className="thumbnail raise-hover clearfix" style={{background:'burlywood',minHeight:270,maxHeight:450,overflowY:'scroll',padding:25,paddingBottom:10}}> 
           {this.ejectForReview()}
           {this.emptyReviewList()}
        </div>
        <div className="thumbnail raise-hover clearfix" style={{padding:25,paddingBottom:10}}>
          <center>
            <h3>Kitchen <span style={{border:'solid 2px #ccc',width:'100px'}} className="inline"></span> Center </h3>
           {this.listComparison()} 
          </center>
          <div className="float-right">
            <button data-toggle="modal" data-target="#attend-modal" onClick ={()=>{ this.setState({editable:this.state.inFocus})}}className="btn btn-danger little-margin">Correct this</button>
           
            {/* <button className="btn btn-success  little-margin" onClick ={()=>{ this.verify()}}>
              Seal
              <span style={{marginLeft:1,display:'none'}}><i class="fa fa-spinner fa-spin"></i></span>  
            </button> */}
          </div>
        </div>
        {/* ================================MODAL AREA====================== */}
        <div className="modal fade" id="attend-modal"> 
            <div className="modal-dialog modal-md">
              <div className="modal-content">
                <div className="modal-header clearfix">
                <h4 className="modal-title">Edit</h4>
                  <h1 className="close" data-dismiss="modal">
                  <i className="fa fa-close"></i>
                  </h1>
                </div>
                <div className="modal-body" style={{maxHeight:'450px',overflowY:'scroll'}}>
                  <Edit 
                    delete = {this.deleteFrom}
                    editable={this.state.editable}
                    streamEdits = {this.streamEdits}
                  />
                  {/* <p> <b>Once you have dealt with issues that caused the mismatch, feel free to change to the new values</b></p>
                  <h5 className="text text-primary">Structure [ (item):(amount):(price of one) ] Example (cake:24:10) means 24 cakes, and each cake costs 10ksh</h5>
                    <small className="text text-danger">Only change the numbers, unless you have to add more items<br/> in which case, follow the structure, above</small> */}
                    {/* <div style={{display:'none'}}>
                      <h5>Values from the kitchen</h5>
                      <textarea className="form-control"ref="kitchen_edit" rows="4" ></textarea>
                      <h5>Values from center</h5>
                      <textarea className="form-control" ref="center_edit" rows="4" ></textarea>
                    </div> */}
                </div>
                <div className="modal-footer">
                  <button className="btn btn-danger" data-dismiss="modal">Come back later</button>
                  <button className="btn btn-primary" onClick={()=>{this.sealAfterEdit()}}>Submit these values
                  <span id="b-spinner" style={{marginLeft:1,display:'none'}}><i className="fa fa-spinner fa-spin"></i></span>
                  </button>
                </div>
              </div>
            </div>
        </div>
      </div>
    )
  }
}

if (document.getElementById('react-manager-div')) {
  ReactDOM.render(<Manager />, document.getElementById('react-manager-div'));
}

