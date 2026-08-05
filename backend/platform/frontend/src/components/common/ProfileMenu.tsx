import {
  Avatar,
  Divider,
  IconButton,
  ListItemIcon,
  Menu,
  MenuItem,
} from '@mui/material'

import PersonIcon from '@mui/icons-material/Person'
import LogoutIcon from '@mui/icons-material/Logout'

import { useState } from 'react'
import { useNavigate } from 'react-router-dom'

import { useAuth } from '../../context/AuthContext'

export default function ProfileMenu() {
  const navigate = useNavigate()

  const { user, logout } = useAuth()

  const [anchorEl, setAnchorEl] =
    useState<null | HTMLElement>(null)

  const open = Boolean(anchorEl)

  function handleLogout() {
    logout()
    navigate('/')
  }

  return (
    <>
      <IconButton
        onClick={(e) =>
          setAnchorEl(e.currentTarget)
        }
      >
        <Avatar>
          {user?.name?.charAt(0) ?? 'A'}
        </Avatar>
      </IconButton>

      <Menu
        anchorEl={anchorEl}
        open={open}
        onClose={() => setAnchorEl(null)}
      >
        <MenuItem disabled>
          {user?.name}
        </MenuItem>

        <MenuItem disabled>
          {user?.email}
        </MenuItem>

        <Divider />

        <MenuItem
          onClick={() => {
            setAnchorEl(null)
            navigate('/profile')
          }}
        >
          <ListItemIcon>
            <PersonIcon fontSize="small" />
          </ListItemIcon>

          My Profile
        </MenuItem>

        <MenuItem
          onClick={handleLogout}
        >
          <ListItemIcon>
            <LogoutIcon fontSize="small" />
          </ListItemIcon>

          Logout
        </MenuItem>

      </Menu>
    </>
  )
}