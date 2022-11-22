import React, { useState } from "react";
import DataService, { ApiErrors, BlogPost, BlogUser } from "../../Service/DataService";
import Button from '@mui/material/Button';
import Dialog from '@mui/material/Dialog';
import DialogActions from '@mui/material/DialogActions';
import DialogContent from '@mui/material/DialogContent';
import DialogTitle from '@mui/material/DialogTitle';
import TextField from '@mui/material/TextField';
import Box from '@mui/material/Box';
import ApiErrorsComponent from "../Component/ApiErrorsComponent";
import TextareaAutosize from '@mui/material/TextareaAutosize';
import InputLabel from '@mui/material/InputLabel';
import MenuItem from '@mui/material/MenuItem';
import FormControl from '@mui/material/FormControl';
import Select, { SelectChangeEvent } from '@mui/material/Select';
import { FILE_SIZE_MAX_UPLOAD_SIZE } from "../../config";
import { convertFileToBase64, imagePath } from "../../Tools/Functions";
import DeleteIcon from '@mui/icons-material/Delete';

type Props = {
  title: string,
  post?: BlogPost
  onClose: (refresh: boolean) => void,
  dataService: DataService,
};

export default function PostForm(props: Props) {

  const [errors, setErrors] = useState<ApiErrors>();
  const [loading, setLoading] = useState<boolean>(false);
  const [featured, setFeatured] = useState(props.post ? props.post.featured : '');
  const [image, setImage] = useState(props.post ? props.post.image : '');
  console.log(props.post)
  const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setLoading(true);

    const data = new FormData(event.currentTarget);

    const params: any = {
      title: data.get('title'),
      featured,
      image,
      content: data.get('content'),
    };

    if (props.post) {
      params['id'] = props.post.id;
    }

    const result = await props.dataService.savePost(params);
    setLoading(false);

    if (undefined !== result['errors']) {
      setErrors(result['errors']);
    } else {
      props.onClose(true);
    }
  };

  // @TODO check for correct type
  const handleFile = async (event: any) => {
    try {
      const imageInBase64 = await convertFileToBase64(event.target.files[0]);
      setImage(imageInBase64);
    } catch (error) {
      setErrors([{
        property: 'image',
        message: 'Unable to upload image',
      }]);
    }

  };


  return (

    <Dialog
      open={true}
      onClose={() => { props.onClose(false) }}
      fullWidth={true}
      maxWidth={'lg'}
    >
      <Box component="form" onSubmit={handleSubmit} noValidate sx={{ mt: 1 }}>
        <DialogTitle id="alert-dialog-title">
          {props.title}
        </DialogTitle>
        <DialogContent>

          {errors && (
            <ApiErrorsComponent errors={errors} />
          )}

          <TextField
            required
            id="title"
            name="title"
            label="Title"
            fullWidth
            autoComplete="given-name"
            variant="standard"
            defaultValue={props.post ? props.post.title : ''}
            autoFocus
          />

          <br /> <br />

          <FormControl variant="standard" style={{ minWidth: 120 }}>
            <InputLabel>Featured *</InputLabel>
            <Select
              defaultValue={props.post ? props.post.featured : ''}
              label="Featured *"
              onChange={(event: SelectChangeEvent) => {
                setFeatured(event.target.value);
              }}
            >
              <MenuItem value={'0'}>No</MenuItem>
              <MenuItem value={'1'}>Yes</MenuItem>
            </Select>
          </FormControl>

          <br /> <br />

          {image.length > 0 && (
            <>
              <Box component="span" sx={{ p: 2, border: '1px dashed grey', display: 'inline-block', position: 'relative' }}>
                <img src={imagePath(image)} height={100} />

                <Button size="small" variant="contained" color="error" onClick={() => {
                  setImage('');
                }} style={{
                  position: 'absolute',
                  top: 0,
                  right: 0,
                  minWidth: 0,
                }}>
                  <DeleteIcon />
                </Button>
              </Box>

              <br /> <br />
            </>
          )}

          <Button variant="contained" component="label">
            Upload Post Image
            <input hidden accept="image/*" type="file" size={FILE_SIZE_MAX_UPLOAD_SIZE} onChange={handleFile} />
          </Button>


          <br /> <br />

          <TextareaAutosize
            required
            id="content"
            name="content"
            // label="Content"
            defaultValue={props.post ? props.post.content : ''}
            minRows={5}
            style={{
              width: '100%',
            }}

          />

        </DialogContent>
        <DialogActions>
          <Button
            disabled={loading}
            onClick={() => { props.onClose(false) }}
          >Close</Button>
          <Button disabled={loading} type="submit" >
            Save
          </Button>
        </DialogActions>
      </Box>
    </Dialog >

  );
}
