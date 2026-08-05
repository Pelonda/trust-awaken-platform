import {
  AppBar,
  Avatar,
  Badge,
  Box,
  CssBaseline,
  Divider,
  Drawer,
  IconButton,
  List,
  ListItemButton,
  ListItemIcon,
  ListItemText,
  Toolbar,
  Typography,
} from '@mui/material'

import DashboardIcon from '@mui/icons-material/Dashboard'
import SchoolIcon from '@mui/icons-material/School'
import PeopleIcon from '@mui/icons-material/People'
import EventIcon from '@mui/icons-material/Event'
import FactCheckIcon from '@mui/icons-material/FactCheck'
import WorkspacePremiumIcon from '@mui/icons-material/WorkspacePremium'
import BusinessIcon from '@mui/icons-material/Business'
import AdminPanelSettingsIcon from '@mui/icons-material/AdminPanelSettings'
import NotificationsNoneIcon from '@mui/icons-material/NotificationsNone'
import DarkModeOutlinedIcon from '@mui/icons-material/DarkModeOutlined'
import KeyboardArrowDownIcon from '@mui/icons-material/KeyboardArrowDown'


import { useState } from 'react'
import { Outlet, useLocation, useNavigate } from 'react-router-dom'

import BreadcrumbsBar from '../common/BreadcrumbsBar'
import GlobalSearch from '../common/GlobalSearch'
import NotificationDrawer from '../common/NotificationDrawer'

const drawerWidth = 270

export default function DashboardLayout() {
  const navigate = useNavigate()
  const location = useLocation()

  const [search, setSearch] = useState('')
  const [notificationsOpen, setNotificationsOpen] = useState(false)

  const menu = [
    {
      group: 'Overview',
      items: [
        {
          title: 'Dashboard',
          icon: <DashboardIcon />,
          url: '/dashboard',
        },
      ],
    },
    {
      group: 'Training',
      items: [
        {
          title: 'Programs',
          icon: <SchoolIcon />,
          url: '/programs',
        },
        {
          title: 'Sessions',
          icon: <EventIcon />,
          url: '/sessions',
        },
        {
          title: 'Participants',
          icon: <PeopleIcon />,
          url: '/participants',
        },
      ],
    },
    {
      group: 'Operations',
      items: [
        {
          title: 'Attendance',
          icon: <FactCheckIcon />,
          url: '/attendance',
        },
        {
          title: 'Credentials',
          icon: <WorkspacePremiumIcon />,
          url: '/credentials',
        },
      ],
    },
    {
      group: 'Administration',
      items: [
        {
          title: 'Organizations',
          icon: <BusinessIcon />,
          url: '#',
        },
        {
          title: 'Users',
          icon: <AdminPanelSettingsIcon />,
          url: '#',
        },
      ],
    },
  ]

  return (
    <Box sx={{ display: 'flex' }}>
      <CssBaseline />

      <AppBar
        elevation={0}
        color="inherit"
        position="fixed"
        sx={{
          borderBottom: '1px solid #e5e7eb',
          zIndex: 1300,
        }}
      >
        <Toolbar>

          <Box
            sx={{
              flexGrow: 1,
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'space-between',
              mr: 3,
            }}
          >

            <Box>

              <Typography
                variant="h6"
                fontWeight={700}
              >
                Trust AWAKEN
              </Typography>

              <Typography
                variant="caption"
                color="text.secondary"
              >
                Global CyberSafe
              </Typography>

            </Box>

            <GlobalSearch
              value={search}
              onChange={setSearch}
            />

          </Box>

          <IconButton
            onClick={() =>
              setNotificationsOpen(true)
            }
          >
            <Badge
              badgeContent={3}
              color="error"
            >
              <NotificationsNoneIcon />
            </Badge>
          </IconButton>

          <IconButton sx={{ ml: 1 }}>
            <DarkModeOutlinedIcon />
          </IconButton>

          <Box
            sx={{
              display: 'flex',
              alignItems: 'center',
              ml: 3,
              px: 1,
              py: 0.5,
              borderRadius: 2,
              cursor: 'pointer',

              '&:hover': {
                backgroundColor: '#f3f4f6',
              },
            }}
          >

            <Avatar
              sx={{
                width: 34,
                height: 34,
                mr: 1.5,
              }}
            >
              A
            </Avatar>

            <Box>

              <Typography
                fontWeight={600}
                lineHeight={1.2}
              >
                Administrator
              </Typography>

              <Typography
                variant="caption"
                color="text.secondary"
              >
                administrator@awaken.org
              </Typography>

            </Box>

            <KeyboardArrowDownIcon
              sx={{ ml: 1 }}
            />

          </Box>

        </Toolbar>

      </AppBar>

      <Drawer
        variant="permanent"
        sx={{
          width: drawerWidth,

          '& .MuiDrawer-paper': {
            width: drawerWidth,
            boxSizing: 'border-box',
            borderRight: '1px solid #e5e7eb',
          },
        }}
      >

        <Toolbar />

        {menu.map((section) => (

          <Box key={section.group}>

            <Typography
              sx={{
                px: 3,
                pt: 3,
                pb: 1,
                fontSize: 12,
                fontWeight: 700,
                color: 'text.secondary',
                textTransform: 'uppercase',
              }}
            >
              {section.group}
            </Typography>

            <List dense>

              {section.items.map((item) => (

                <ListItemButton
                  key={item.title}
                  selected={
                    location.pathname === item.url
                  }
                  onClick={() =>
                    item.url !== '#'
                      ? navigate(item.url)
                      : undefined
                  }
                >

                  <ListItemIcon>
                    {item.icon}
                  </ListItemIcon>

                  <ListItemText
                    primary={item.title}
                  />

                </ListItemButton>

              ))}

            </List>

            <Divider sx={{ mt: 1 }} />

          </Box>

        ))}

      </Drawer>

      <Box
        component="main"
        sx={{
          flexGrow: 1,
          background: '#f4f7fb',
          minHeight: '100vh',
          p: 4,
        }}
      >

        <Toolbar />

        <BreadcrumbsBar />

        <Outlet />

      </Box>

      <NotificationDrawer
        open={notificationsOpen}
        onClose={() =>
          setNotificationsOpen(false)
        }
      />

    </Box>
  )
}