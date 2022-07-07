import model2 from '../models/model'

const model = new model2();

export default class Controller{
  getOne(req, res, next){
    let id= req.params.id
    model.getOne(id, (err, data) => {
      if(err){
        res.render("error",err);
      }
      else{
        res.status(200).json(data);
      }
    })
  }
}
