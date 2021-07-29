var express = require('express');
const fileUpload = require('express-fileupload');
const cors = require('cors');
const fs = require('fs');
const sharp = require('sharp');

const { uid } =  require('uid');

function onFileupload(req, res) {

    let file = req['files'].file;

    console.log("File uploaded: ", file);

    const id = uid();

    const ext = file.name.substring(file.name.lastIndexOf('.') + 1).toLowerCase();

    if( ext === 'png' || ext === 'jpg' || ext === 'jpeg' || ext === 'gif') {
        sharp(file.data)
            .resize({width: 320, height : 320 })
            .toFile(__dirname + '/upload/' + id + '-profile.png' )
            .then(()=> {
                return sharp(file.data)
                    .toFile(__dirname + '/upload/' + id + '.png' )
            }).then(()=> {
                return res.status(200).json({file: id + '.png'});
            })
            .catch((error) => {
                return res.status(500).json(error);
            })
    } else {
        fs.writeFile(__dirname + '/upload/' + id + '.' + ext, file.data, (error) => {
            if (error) {
                return res.status(500).json(error);
            }
            return res.status(200).json({file : id + '.' + ext });
        })
    }
}



function readFile( req, res ) {
    console.log(req.params.filename);
    const file = __dirname + '/upload/' + req.params.filename;
    return res.download(file);
}

function deletePicture( req, res ) {
    const filename = __dirname + '/upload/' + req.params.filename;
    const radical = req.params.filename.substr(0, req.params.filename.lastIndexOf('.'));
    const profile = __dirname + '/upload/' + radical + '-profile.png';

    try {
        fs.unlinkSync(filename);
        fs.unlinkSync(profile);
    } catch(error) {
        return res.status(500).json({error});
    }
    return res.status(200).json({ok: true});
}

var app = express();
app.use(cors())
app.use(fileUpload());

app.route('/upload').post(onFileupload);

app.route('/file/:filename').get(readFile)

app.route('/picture/:filename').delete(deletePicture);


app.set('port', process.env.PORT || 80);
app.listen(app.get('port'));
console.log("app listening on port: " , app.get('port'));
