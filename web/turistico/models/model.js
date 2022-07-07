import conn from './connection'

export default class Model{
	getAll(callback) {
		conn.query('SELECT * FROM conversaciones', callback);
	}
	getOne(data, callback) {
		conn.query('SELECT * FROM conversaciones WHERE id = ?', data, callback)
	}
	save(data, callback){
		conn.query('SELECT * FROM conversaciones WHERE id = ?', data.client_number_id, (err, rows)=>{
			console.log(rows.length)

			if(err)
			{
				return err
			}
			else
			{
				if(rows.length>=1){
					return conn.query('UPDATE conversaciones SET ? WHERE id = ?', [data, data.client_number_id], callback);
				}
				else{
					return conn.query('INSERT INTO conversaciones SET ?', data, callback);
				}
			}
		})
	}
	delete(data, callback){
		conn.query('DELETE FROM conversaciones where id = ?', data, callback)
	}
}
