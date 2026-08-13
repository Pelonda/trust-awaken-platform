import {
  useMemo,
  useState,
} from 'react'

import {
  Box,
  Button,
  Stack,
} from '@mui/material'

import UploadFileIcon from '@mui/icons-material/UploadFile'

import {
  useMutation,
  useQueryClient,
} from '@tanstack/react-query'

import type {
  GridColDef,
} from '@mui/x-data-grid'

import AppToolbar from '../../components/common/AppToolbar'
import AppSkeleton from '../../components/common/AppSkeleton'
import EmptyState from '../../components/common/EmptyState'
import SearchBar from '../../components/common/SearchBar'
import ConfirmDialog from '../../components/dialogs/ConfirmDialog'
import AppDataGrid from '../../components/tables/AppDataGrid'
import * as Toast from '../../components/common/AppToast'

import {
  deleteParticipant,
} from './api'

import {
  useParticipants,
} from './hooks'

import ParticipantDialog from './ParticipantDialog'
import ParticipantImportDialog from './ParticipantImportDialog'

import type {
  Participant,
} from './types'

export default function ParticipantsPage() {

  const queryClient =
    useQueryClient()

  const {
    data,
    isLoading,
  } =
    useParticipants()

  const [
    search,
    setSearch,
  ] =
    useState('')

  const [
    createOpen,
    setCreateOpen,
  ] =
    useState(false)

  const [
    importOpen,
    setImportOpen,
  ] =
    useState(false)

  const [
    confirmOpen,
    setConfirmOpen,
  ] =
    useState(false)

  const [
    selectedParticipant,
    setSelectedParticipant,
  ] =
    useState<Participant | null>(
      null,
    )

  /*
  |--------------------------------------------------------------------------
  | Delete Participant
  |--------------------------------------------------------------------------
  */

  const deleteMutation =
    useMutation({

      mutationFn:
        deleteParticipant,

      onSuccess:
        async () => {

          Toast.success(
            'Participant deleted successfully.',
          )

          await queryClient.invalidateQueries({
            queryKey: [
              'participants',
            ],
          })

        },

      onError:
        () => {

          Toast.error(
            'Unable to delete participant.',
          )

        },

    })

  /*
  |--------------------------------------------------------------------------
  | Rows
  |--------------------------------------------------------------------------
  */

  const rows =
    useMemo(
      () => {

        if (
          !data?.data
        ) {
          return []
        }

        const query =
          search
            .trim()
            .toLowerCase()

        if (
          query === ''
        ) {
          return data.data
        }

        return data.data.filter(
          (
            participant:
              Participant,
          ) => {

            const code =
              participant
                .participant_code
                ?.toLowerCase() ??
              ''

            const firstName =
              participant
                .first_name
                ?.toLowerCase() ??
              ''

            const lastName =
              participant
                .last_name
                ?.toLowerCase() ??
              ''

            const email =
              participant
                .email
                ?.toLowerCase() ??
              ''

            return (
              code.includes(
                query,
              ) ||
              firstName.includes(
                query,
              ) ||
              lastName.includes(
                query,
              ) ||
              email.includes(
                query,
              )
            )

          },
        )

      },
      [
        data,
        search,
      ],
    )

  /*
  |--------------------------------------------------------------------------
  | Columns
  |--------------------------------------------------------------------------
  */

  const columns:
    GridColDef[] = [

      {
        field:
          'participant_code',

        headerName:
          'Code',

        flex:
          1,

        minWidth:
          130,
      },

      {
        field:
          'first_name',

        headerName:
          'First Name',

        flex:
          1,

        minWidth:
          140,
      },

      {
        field:
          'last_name',

        headerName:
          'Last Name',

        flex:
          1,

        minWidth:
          140,
      },

      {
        field:
          'email',

        headerName:
          'Email',

        flex:
          2,

        minWidth:
          220,
      },

      {
        field:
          'status',

        headerName:
          'Status',

        flex:
          1,

        minWidth:
          120,
      },

    ]

  /*
  |--------------------------------------------------------------------------
  | Loading
  |--------------------------------------------------------------------------
  */

  if (
    isLoading
  ) {
    return (
      <AppSkeleton />
    )
  }

  /*
  |--------------------------------------------------------------------------
  | Toolbar Content
  |--------------------------------------------------------------------------
  */

  const toolbarContent = (

    <Stack
      spacing={1.5}
    >

      <Stack
        direction={{
          xs: 'column',
          sm: 'row',
        }}
        spacing={1}
        alignItems={{
          xs: 'stretch',
          sm: 'center',
        }}
      >

        <Box
          sx={{
            flex: 1,
          }}
        >

          <SearchBar
            value={search}
            onChange={setSearch}
          />

        </Box>

        <Button
          variant="outlined"
          startIcon={
            <UploadFileIcon />
          }
          onClick={
            () =>
              setImportOpen(
                true,
              )
          }
        >
          Import CSV / Excel
        </Button>

      </Stack>

    </Stack>

  )

  /*
  |--------------------------------------------------------------------------
  | Empty State
  |--------------------------------------------------------------------------
  */

  if (
    rows.length === 0
  ) {
    return (

      <>

        <AppToolbar
          title="Participants"
          subtitle="Manage participants and program enrollment"
          buttonText="New Participant"
          onAdd={
            () =>
              setCreateOpen(
                true,
              )
          }
        >

          {toolbarContent}

        </AppToolbar>

        <EmptyState
          title={
            search
              ? 'No Matching Participants'
              : 'No Participants'
          }
          subtitle={
            search
              ? 'No participants match your search.'
              : 'Add a participant manually or import a CSV / Excel file.'
          }
          button="New Participant"
          onClick={
            () =>
              setCreateOpen(
                true,
              )
          }
        />

        <ParticipantDialog
          open={createOpen}
          onClose={
            () =>
              setCreateOpen(
                false,
              )
          }
        />

        <ParticipantImportDialog
          open={importOpen}
          onClose={
            () =>
              setImportOpen(
                false,
              )
          }
        />

      </>

    )
  }

  /*
  |--------------------------------------------------------------------------
  | Participant List
  |--------------------------------------------------------------------------
  */

  return (

    <>

      <AppToolbar
        title="Participants"
        subtitle="Manage participants and program enrollment"
        buttonText="New Participant"
        onAdd={
          () =>
            setCreateOpen(
              true,
            )
        }
        onRefresh={
          async () => {

            await queryClient.invalidateQueries({
              queryKey: [
                'participants',
              ],
            })

          }
        }
        onExport={
          () =>
            Toast.info(
              'Participant export coming soon.',
            )
        }
        onFilter={
          () =>
            Toast.info(
              'Participant filters coming soon.',
            )
        }
      >

        {toolbarContent}

      </AppToolbar>

      <AppDataGrid
        rows={rows}
        columns={columns}
        onEdit={
          participant =>
            console.log(
              'Edit',
              participant,
            )
        }
        onDelete={
          participant => {

            setSelectedParticipant(
              participant,
            )

            setConfirmOpen(
              true,
            )

          }
        }
      />

      {/*
      |--------------------------------------------------------------------------
      | Delete Confirmation
      |--------------------------------------------------------------------------
      */}

      <ConfirmDialog
        open={confirmOpen}
        title="Delete Participant"
        message={
          `Delete "${
            selectedParticipant
              ?.first_name ??
            ''
          } ${
            selectedParticipant
              ?.last_name ??
            ''
          }"?`
        }
        loading={
          deleteMutation
            .isPending
        }
        onCancel={
          () => {

            setConfirmOpen(
              false,
            )

            setSelectedParticipant(
              null,
            )

          }
        }
        onConfirm={
          () => {

            if (
              !selectedParticipant
            ) {
              return
            }

            deleteMutation.mutate(
              selectedParticipant
                .uuid,
              {
                onSuccess:
                  () => {

                    setConfirmOpen(
                      false,
                    )

                    setSelectedParticipant(
                      null,
                    )

                  },
              },
            )

          }
        }
      />

      {/*
      |--------------------------------------------------------------------------
      | Manual Participant
      |--------------------------------------------------------------------------
      */}

      <ParticipantDialog
        open={createOpen}
        onClose={
          () =>
            setCreateOpen(
              false,
            )
        }
      />

      {/*
      |--------------------------------------------------------------------------
      | CSV / Excel Import
      |--------------------------------------------------------------------------
      */}

      <ParticipantImportDialog
        open={importOpen}
        onClose={
          () =>
            setImportOpen(
              false,
            )
        }
      />

    </>

  )
}