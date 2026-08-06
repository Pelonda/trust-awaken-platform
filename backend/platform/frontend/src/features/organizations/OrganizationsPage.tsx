import { useMemo, useState } from 'react'
import { useMutation, useQueryClient } from '@tanstack/react-query'
import type { GridColDef } from '@mui/x-data-grid'

import AppDataGrid from '../../components/tables/AppDataGrid'
import AppSkeleton from '../../components/common/AppSkeleton'
import ConfirmDialog from '../../components/dialogs/ConfirmDialog'
import EmptyState from '../../components/common/EmptyState'
import PageContainer from '../../components/common/PageContainer'
import SearchBar from '../../components/common/SearchBar'
import * as Toast from '../../components/common/AppToast'

import {
  deleteOrganization,
} from './api'

import {
  useOrganizations,
} from './hooks'

import OrganizationDialog from './OrganizationDialog'

export default function OrganizationsPage() {

  const queryClient = useQueryClient()

  const { data, isLoading } =
    useOrganizations()

  const [search, setSearch] =
    useState('')

  const [createOpen, setCreateOpen] =
    useState(false)

  const [confirmOpen, setConfirmOpen] =
    useState(false)

  const [selectedOrganization, setSelectedOrganization] =
    useState<any>(null)

  const deleteMutation = useMutation({

    mutationFn: deleteOrganization,

    onSuccess: async () => {

      Toast.success(
        'Organization deleted.'
      )

      await queryClient.invalidateQueries({
        queryKey: ['organizations'],
      })

    },

    onError: () => {

      Toast.error(
        'Unable to delete organization.'
      )

    },

  })

  const rows = useMemo(() => {

    if (!data?.data) {
      return []
    }

    return data.data.filter((organization: any) =>

      organization.display_name
        ?.toLowerCase()
        .includes(search.toLowerCase()) ||

      organization.legal_name
        ?.toLowerCase()
        .includes(search.toLowerCase())

    )

  }, [data, search])

  const columns: GridColDef[] = [

    {
      field: 'display_name',
      headerName: 'Display Name',
      flex: 2,
    },

    {
      field: 'legal_name',
      headerName: 'Legal Name',
      flex: 2,
    },

    {
      field: 'organization_type',
      headerName: 'Type',
      flex: 1,
    },

    {
      field: 'status',
      headerName: 'Status',
      flex: 1,
    },

  ]

  if (isLoading) {
    return <AppSkeleton />
  }

  if (rows.length === 0) {

    return (

      <PageContainer
        title="Organizations"
        subtitle="Manage organizations"
        buttonText="New Organization"
        onAdd={() => setCreateOpen(true)}
      >

        <SearchBar
          value={search}
          onChange={setSearch}
        />

        <EmptyState
          title="No Organizations"
          subtitle="Create your first organization."
          button="New Organization"
          onClick={() => setCreateOpen(true)}
        />

        <OrganizationDialog
          open={createOpen}
          onClose={() =>
            setCreateOpen(false)
          }
        />

      </PageContainer>

    )

  }

  return (

    <PageContainer
      title="Organizations"
      subtitle="Manage organizations"
      buttonText="New Organization"
      onAdd={() => setCreateOpen(true)}
    >

      <SearchBar
        value={search}
        onChange={setSearch}
      />

      <AppDataGrid
        rows={rows}
        columns={columns}
        onEdit={(organization) =>
          console.log(organization)
        }
        onDelete={(organization) => {

          setSelectedOrganization(
            organization
          )

          setConfirmOpen(true)

        }}
      />

      <ConfirmDialog
        open={confirmOpen}
        title="Delete Organization"
        message={`Delete "${selectedOrganization?.display_name}"?`}
        loading={deleteMutation.isPending}
        onCancel={() => {

          setConfirmOpen(false)

          setSelectedOrganization(null)

        }}
        onConfirm={() => {

          if (!selectedOrganization) {
            return
          }

          deleteMutation.mutate(
            selectedOrganization.uuid,
            {
              onSuccess: () => {

                setConfirmOpen(false)

                setSelectedOrganization(null)

              },
            }
          )

        }}
      />

      <OrganizationDialog
        open={createOpen}
        onClose={() =>
          setCreateOpen(false)
        }
      />

    </PageContainer>

  )

}