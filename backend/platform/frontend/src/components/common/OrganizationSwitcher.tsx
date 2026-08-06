import {
  FormControl,
  MenuItem,
  Select,
  Typography,
} from '@mui/material'

import { useEffect, useState } from 'react'

import { getOrganizations } from '../../features/organizations/api'

interface Organization {
  uuid: string
  display_name: string
}

export default function OrganizationSwitcher() {

  const [organizations, setOrganizations] =
    useState<Organization[]>([])

  const [selected, setSelected] =
    useState('')

  useEffect(() => {

    async function load() {

      try {

        const response =
          await getOrganizations()

        const rows =
          response.data ?? []

        setOrganizations(rows)

        const stored =
          localStorage.getItem(
            'organization_uuid'
          )

        if (
          stored &&
          rows.find(
            o => o.uuid === stored
          )
        ) {

          setSelected(stored)

          return

        }

        if (rows.length > 0) {

          setSelected(rows[0].uuid)

          localStorage.setItem(
            'organization_uuid',
            rows[0].uuid
          )

        }

      } catch (error) {

        console.error(error)

      }

    }

    load()

  }, [])

  function change(uuid: string) {

    setSelected(uuid)

    localStorage.setItem(
      'organization_uuid',
      uuid
    )

    window.location.reload()

  }

  return (

    <FormControl
      size="small"
      sx={{
        minWidth: 260,
      }}
    >

      <Select
        value={selected}
        onChange={(e) =>
          change(
            e.target.value
          )
        }
      >

        {organizations.map(
          (organization) => (

            <MenuItem
              key={organization.uuid}
              value={organization.uuid}
            >

              <Typography
                noWrap
              >
                {organization.display_name}
              </Typography>

            </MenuItem>

          )
        )}

      </Select>

    </FormControl>

  )

}