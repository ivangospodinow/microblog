import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import Table from '@mui/material/Table';
import TableBody from '@mui/material/TableBody';
import TableCell from '@mui/material/TableCell';
import TableHead from '@mui/material/TableHead';
import TableRow from '@mui/material/TableRow';
import Typography from '@mui/material/Typography';
import { BlogProps } from '../Blog';
import { ADMIN_LIST_RECORDS_COUNT, HOMEPAGE_LAST_POSTS_COUNT } from '../../config';
import { ApiErrors, BlogUser, BlogUsers } from "../../Service/DataService";
import Button from '@mui/material/Button';

import UserForm from "../Form/UserForm";
import AddIcon from '@mui/icons-material/Add';
import DeleteForeverIcon from '@mui/icons-material/DeleteForever';
import UserDeleteForm from "../Form/UserDeleteForm";
import { Skeleton } from '@mui/material';
import ApiErrorsComponent from "./ApiErrorsComponent";
import ButtonGroup from '@mui/material/ButtonGroup';
import SpacerComponent from "./SpacerComponent";


function createRow(row: {
  key: any,
  id: any,
  username: any,
  buttons: any,
}) {
  return (
    <TableRow key={row.key}>
      <TableCell>{row.id}</TableCell>
      <TableCell>{row.username}</TableCell>
      <TableCell align="right">
        {row.buttons}
      </TableCell>
    </TableRow>
  );
}


export default function UsersTable(props: BlogProps) {

  const [userToDelete, setUserToDelete] = useState<BlogUser>();

  const [showUserForm, setShowUserForm] = useState<boolean>(false);
  const [userToEdit, setUserToEdit] = useState<BlogUser | null>();
  const [users, setUsers] = useState<BlogUsers>(undefined);
  const [usersLoaded, setUsersLoaded] = useState(false);
  const [errors, setErrors] = useState<ApiErrors>(undefined);

  let { page } = useParams<{ page: string }>();
  const currentPage = parseInt(page || '1');

  useEffect(() => {

    (async () => {
      const result = await props.dataService.getUsers({
        list: {
          limit: ADMIN_LIST_RECORDS_COUNT,
          page: currentPage,
        },
      });
      setUsersLoaded(true);
      setUsers(result.list);
      setErrors(result.errors || undefined);
    })();


  }, [usersLoaded, page]);

  return (
    <React.Fragment>
      <Typography component="h2" variant="h6" color="primary" gutterBottom>
        Users
        <Button variant="contained" size="small" onClick={() => {
          setUserToEdit(null);
          setShowUserForm(true);
        }} style={{ float: 'right' }}>
          <AddIcon />
        </Button>
      </Typography>

      <ApiErrorsComponent errors={errors} />

      <Table size="small">
        <TableHead>
          <TableRow>
            <TableCell>Id</TableCell>
            <TableCell>Username</TableCell>
            <TableCell align="right"></TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {users && users.map((row) => {
            return createRow({
              key: row.id,
              id: row.id,
              username: row.username,
              buttons: (
                <>
                  <Button variant="outlined" size="small" onClick={() => {
                    setUserToEdit(row);
                    setShowUserForm(true);
                  }}>
                    Edit
                  </Button>
                  &nbsp;
                  <Button variant="outlined" size="small" onClick={() => {
                    setUserToDelete(row);
                  }} style={{
                    minWidth: 0,
                  }}>
                    <DeleteForeverIcon />
                  </Button>
                </>
              )
            });
          })}
          {!usersLoaded && undefined === users && Array(HOMEPAGE_LAST_POSTS_COUNT).fill(0).map((_, key: number) => {
            return createRow({
              key,
              id: (<Skeleton variant="rectangular" width={'100%'} height={'1em'} />),
              username: (<Skeleton variant="rectangular" width={'100%'} height={'1em'} />),
              buttons: (<Skeleton variant="rectangular" width={'100%'} height={'1em'} />),
            });
          })}
        </TableBody>
      </Table>

      <SpacerComponent />

      <div>
        <ButtonGroup variant="contained" aria-label="outlined primary button group">
          <Button
            variant="contained"
            onClick={() => {
              window.location.href = '/admin/posts/' + (currentPage - 1);
            }}
            disabled={!(usersLoaded && currentPage > 1)}
          >
            Prev Page
          </Button>

          <Button
            variant="contained"
            onClick={() => {
              window.location.href = '/admin/posts/' + (currentPage + 1);
            }}
            disabled={!(usersLoaded && (users && users.length >= HOMEPAGE_LAST_POSTS_COUNT))}
          >
            Next Page
          </Button>
        </ButtonGroup>
      </div>

      {showUserForm && (
        <UserForm title={'Edit user'} user={userToEdit || undefined} onClose={(refresh: boolean) => {
          setUserToEdit(null);
          setShowUserForm(false);

          if (refresh) {
            setUsers([]);
            setUsersLoaded(false);
          }
        }} dataService={props.dataService} />
      )}

      {userToDelete && (
        <UserDeleteForm title={'Delete user ' + userToDelete.username} user={userToDelete} onClose={(refresh: boolean) => {
          setUserToDelete(undefined);
          if (refresh) {
            setUsers([]);
            setUsersLoaded(false);
          }
        }} dataService={props.dataService} />
      )}
    </React.Fragment>
  );
}
