import {
  AppBar,
  Box,
  CssBaseline,
  Drawer,
  List,
  ListItemButton,
  ListItemText,
  Toolbar,
  Typography,
} from '@mui/material'

import { Outlet, useNavigate } from 'react-router-dom'

const drawerWidth = 260

export default function DashboardLayout() {
  const navigate = useNavigate()

  return (
    <Box sx={{ display: 'flex' }}>
      <CssBaseline />

      <AppBar
        position="fixed"
        sx={{
          zIndex: 1300,
        }}
      >
        <Toolbar>
          <Typography variant="h6">
            Trust AWAKEN
          </Typography>
        </Toolbar>
      </AppBar>

      <Drawer
        variant="permanent"
        sx={{
          width: drawerWidth,
          '& .MuiDrawer-paper': {
            width: drawerWidth,
          },
        }}
      >
        <Toolbar />

        <List>

          <ListItemButton
            onClick={() => navigate('/dashboard')}
          >
            <ListItemText primary="Dashboard" />
          </ListItemButton>

          <ListItemButton
    onClick={() => navigate('/programs')}
>
    <ListItemText primary="Programs" />
</ListItemButton>

          <ListItemButton
    onClick={() => navigate('/participants')}
>
    <ListItemText primary="Participants" />
</ListItemButton>

<ListItemButton
    onClick={() => navigate('/sessions')}
>
    <ListItemText primary="Sessions" />
</ListItemButton>

<ListItemButton
    onClick={() => navigate('/attendance')}
>
    <ListItemText primary="Attendance" />
</ListItemButton>

<ListItemButton
    onClick={() => navigate('/credentials')}
>
    <ListItemText primary="Credentials" />
</ListItemButton>

        </List>

      </Drawer>

      <Box
        component="main"
        sx={{
          flexGrow: 1,
          p: 4,
        }}
      >
        <Toolbar />

        <Outlet />

      </Box>

    </Box>
  )
}