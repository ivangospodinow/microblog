import React, { useState } from "react";
import DataService, { ApiErrors, BlogUser } from "../../Service/DataService";
import Button from '@mui/material/Button';
import Dialog from '@mui/material/Dialog';
import DialogActions from '@mui/material/DialogActions';
import DialogContent from '@mui/material/DialogContent';
import DialogTitle from '@mui/material/DialogTitle';
import TextField from '@mui/material/TextField';
import Box from '@mui/material/Box';
import ApiErrorsComponent from "../Component/ApiErrorsComponent";

type Props = {
  title: string,
  user: BlogUser
  onClose: (refresh: boolean) => void,
  dataService: DataService,
};

export default function UserDeleteForm(props: Props) {

  const [errors, setErrors] = useState<ApiErrors>();
  const [loading, setLoading] = useState<boolean>(false);

  const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setLoading(true);

    const result = await props.dataService.deleteUser(props.user.id);
    setLoading(false);

    if (undefined !== result['errors']) {
      setErrors(result['errors']);
    } else {
      props.onClose(true);
    }
  };

  return (
    <Dialog
      open={true}
      onClose={() => { props.onClose(false) }}
      aria-labelledby="alert-dialog-title"
      aria-describedby="alert-dialog-description"
    >
      <Box component="form" onSubmit={handleSubmit} noValidate sx={{ mt: 1 }}>
        <DialogTitle id="alert-dialog-title">
          {props.title}
        </DialogTitle>
        <DialogContent>

          {errors && (
            <ApiErrorsComponent errors={errors} />
          )}

        </DialogContent>
        <DialogActions>
          <Button
            disabled={loading}
            onClick={() => { props.onClose(false) }}
          >Close</Button>
          <Button disabled={loading} type="submit" >
            Confirm
          </Button>
        </DialogActions>
      </Box>
    </Dialog>
  );
}
