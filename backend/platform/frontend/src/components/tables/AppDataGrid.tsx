import {
  DataGrid,
  type GridColDef,
  type GridRenderCellParams,
} from '@mui/x-data-grid'

import {
  Box,
  IconButton,
  Tooltip,
} from '@mui/material'

import EditIcon from '@mui/icons-material/Edit'
import DeleteIcon from '@mui/icons-material/Delete'
import VisibilityIcon from '@mui/icons-material/Visibility'
import DownloadIcon from '@mui/icons-material/Download'
import BlockIcon from '@mui/icons-material/Block'
import RestoreIcon from '@mui/icons-material/Restore'

import StatusChip from '../common/StatusChip'

interface Props {
  rows: any[]

  columns: GridColDef[]

  onEdit?: (
    row: any,
  ) => void

  onDelete?: (
    row: any,
  ) => void

  onPreview?: (
    row: any,
  ) => void

  onDownload?: (
    row: any,
  ) => void

  /*
   * Optional credential-style lifecycle
   * action.
   *
   * When supplied, this replaces the
   * normal Delete button.
   */
  onLifecycleAction?: (
    row: any,
  ) => void

  lifecycleActionLabel?: (
    row: any,
  ) => string
}

export default function AppDataGrid({
  rows,
  columns,
  onEdit,
  onDelete,
  onPreview,
  onDownload,
  onLifecycleAction,
  lifecycleActionLabel,
}: Props) {
  const finalColumns:
    GridColDef[] = [
      ...columns.map(
        column => ({
          ...column,

          renderCell:
            column.field ===
            'status'
              ? (
                  params:
                    GridRenderCellParams,
                ) => (
                  <StatusChip
                    value={
                      String(
                        params.value,
                      )
                    }
                  />
                )
              : column.renderCell,
        }),
      ),

      {
        field:
          'actions',

        headerName:
          'Actions',

        width:
          190,

        sortable:
          false,

        filterable:
          false,

        renderCell:
          params => {
            const row =
              params.row

            const lifecycleLabel =
              lifecycleActionLabel
                ? lifecycleActionLabel(
                    row,
                  )
                : 'Revoke'

            const isRestore =
              lifecycleLabel
                .toLowerCase()
                .includes(
                  'restore',
                )

            return (
              <>
                {onPreview && (
                  <Tooltip
                    title="Preview"
                  >
                    <IconButton
                      onClick={() =>
                        onPreview(
                          row,
                        )
                      }
                    >
                      <VisibilityIcon
                        fontSize="small"
                      />
                    </IconButton>
                  </Tooltip>
                )}

                {onDownload && (
                  <Tooltip
                    title="Download"
                  >
                    <IconButton
                      onClick={() =>
                        onDownload(
                          row,
                        )
                      }
                    >
                      <DownloadIcon
                        fontSize="small"
                      />
                    </IconButton>
                  </Tooltip>
                )}

                {onEdit && (
                  <Tooltip
                    title="Edit"
                  >
                    <IconButton
                      color="primary"
                      onClick={() =>
                        onEdit(
                          row,
                        )
                      }
                    >
                      <EditIcon
                        fontSize="small"
                      />
                    </IconButton>
                  </Tooltip>
                )}

                {onLifecycleAction ? (
                  <Tooltip
                    title={
                      lifecycleLabel
                    }
                  >
                    <IconButton
                      color={
                        isRestore
                          ? 'primary'
                          : 'error'
                      }
                      onClick={() =>
                        onLifecycleAction(
                          row,
                        )
                      }
                    >
                      {isRestore ? (
                        <RestoreIcon
                          fontSize="small"
                        />
                      ) : (
                        <BlockIcon
                          fontSize="small"
                        />
                      )}
                    </IconButton>
                  </Tooltip>
                ) : (
                  onDelete && (
                    <Tooltip
                      title="Delete"
                    >
                      <IconButton
                        color="error"
                        onClick={() =>
                          onDelete(
                            row,
                          )
                        }
                      >
                        <DeleteIcon
                          fontSize="small"
                        />
                      </IconButton>
                    </Tooltip>
                  )
                )}
              </>
            )
          },
      },
    ]

  return (
    <Box
      sx={{
        height:
          620,

        width:
          '100%',
      }}
    >
      <DataGrid
        rows={
          rows
        }
        columns={
          finalColumns
        }
        getRowId={
          row =>
            row.uuid
        }
        pageSizeOptions={[
          10,
          25,
          50,
        ]}
        disableRowSelectionOnClick
        initialState={{
          pagination: {
            paginationModel: {
              pageSize:
                10,
            },
          },
        }}
      />
    </Box>
  )
}