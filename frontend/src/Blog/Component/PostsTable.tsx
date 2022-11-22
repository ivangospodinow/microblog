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
import { ApiErrors, BlogPost, BlogPosts } from "../../Service/DataService";
import Button from '@mui/material/Button';

import AddIcon from '@mui/icons-material/Add';
import DeleteForeverIcon from '@mui/icons-material/DeleteForever';
import { Skeleton } from '@mui/material';
import PostForm from "../Form/PostForm";
import PostDeleteForm from "../Form/PostDeleteForm";
import ApiErrorsComponent from "./ApiErrorsComponent";
import RemoveRedEyeIcon from '@mui/icons-material/RemoveRedEye';

function createRow(row: {
  key: any,
  id: any,
  title: any,
  buttons: any,
  featured: any,
  createdAt: any,
  createdBy: any,
}) {
  return (
    <TableRow key={row.key}>
      <TableCell>{row.id}</TableCell>
      <TableCell>{row.title}</TableCell>
      <TableCell>{row.featured}</TableCell>
      <TableCell>{row.createdBy}</TableCell>
      <TableCell>{row.createdAt}</TableCell>
      <TableCell align="right">
        {row.buttons}
      </TableCell>
    </TableRow>
  );
}


export default function PostsTable(props: BlogProps) {

  const [postToDelete, setPostToDelete] = useState<BlogPost>();

  const [showPostForm, setShowPostForm] = useState<boolean>(false);
  const [postToEdit, setPostToEdit] = useState<BlogPost | null>();
  const [posts, setPosts] = useState<BlogPosts>(undefined);
  const [postsLoaded, setPostsLoaded] = useState(false);
  const [errors, setErrors] = useState<ApiErrors>(undefined);

  let { page } = useParams<{ page: string }>();
  const currentPage = parseInt(page || '1');

  useEffect(() => {

    (async () => {
      const result = await props.dataService.getPosts({
        list: {
          limit: ADMIN_LIST_RECORDS_COUNT,
          page: currentPage,
        },
      });
      setPostsLoaded(true);
      setPosts(result.list);
      setErrors(result.errors || undefined);

    })();


  }, [postsLoaded, page]);

  return (
    <React.Fragment>
      <Typography component="h2" variant="h6" color="primary" gutterBottom>
        Posts
        <Button variant="contained" size="small" onClick={() => {
          setPostToEdit(null);
          setShowPostForm(true);
        }} style={{ float: 'right' }}>
          <AddIcon />
        </Button>
      </Typography>

      <ApiErrorsComponent errors={errors} />

      <Table size="small">
        <TableHead>
          <TableRow>
            <TableCell>Id</TableCell>
            <TableCell>Title</TableCell>
            <TableCell>Featured</TableCell>
            <TableCell>Created By</TableCell>
            <TableCell>Created At</TableCell>
            <TableCell align="right"></TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {posts && posts.map((post: BlogPost) => {
            return createRow({
              key: post.id,
              id: post.id,
              title: post.title,
              featured: post.featured === '1' ? 'Yes' : 'No',
              createdBy: post.createdByUser.username,
              createdAt: post.createdAt,
              buttons: (
                <>
                  <Button variant="outlined" size="small" onClick={() => {
                    window.location.href = '/post/' + post.id;
                  }}>
                    <RemoveRedEyeIcon />
                  </Button>
                  &nbsp;
                  <Button variant="outlined" size="small" onClick={() => {
                    setPostToEdit(post);
                    setShowPostForm(true);
                  }}>
                    Edit
                  </Button>
                  &nbsp;
                  <Button variant="outlined" size="small" onClick={() => {
                    setPostToDelete(post);
                  }} style={{
                    minWidth: 0,
                  }}>
                    <DeleteForeverIcon />
                  </Button>
                </>
              )
            });
          })}
          {!postsLoaded && undefined === posts && Array(HOMEPAGE_LAST_POSTS_COUNT).fill(0).map((_, key: number) => {
            return createRow({
              key,
              id: (<Skeleton variant="rectangular" width={'100%'} height={'1em'} />),
              title: (<Skeleton variant="rectangular" width={'100%'} height={'1em'} />),
              featured: (<Skeleton variant="rectangular" width={'100%'} height={'1em'} />),
              createdBy: (<Skeleton variant="rectangular" width={'100%'} height={'1em'} />),
              createdAt: (<Skeleton variant="rectangular" width={'100%'} height={'1em'} />),
              buttons: (<Skeleton variant="rectangular" width={'100%'} height={'1em'} />),
            });
          })}
        </TableBody>
      </Table>

      {showPostForm && (
        <PostForm title={'Edit post'} post={postToEdit || undefined} onClose={(refresh: boolean) => {
          setPostToEdit(null);
          setShowPostForm(false);

          if (refresh) {
            setPosts([]);
            setPostsLoaded(false);
          }
        }} dataService={props.dataService} />
      )}

      {postToDelete && (
        <PostDeleteForm title={'Delete post ' + postToDelete.title} post={postToDelete} onClose={(refresh: boolean) => {
          setPostToDelete(undefined);
          if (refresh) {
            setPosts([]);
            setPostsLoaded(false);
          }
        }} dataService={props.dataService} />
      )}
    </React.Fragment>
  );
}
