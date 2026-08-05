import {
  DataGrid,
  type GridColDef,
  type GridRenderCellParams,
} from '@mui/x-data-grid'

import {
  Box,
  IconButton,
} from '@mui/material'

import EditIcon from '@mui/icons-material/Edit'
import DeleteIcon from '@mui/icons-material/Delete'

import StatusChip from '../common/StatusChip'

interface Props {

  rows: any[]

  columns: GridColDef[]

  onEdit?: (row: any) => void

  onDelete?: (row: any) => void

}

export default function AppDataGrid({

  rows,

  columns,

  onEdit,

  onDelete,

}: Props) {

  const finalColumns: GridColDef[] = [

    ...columns.map((column) => ({

      ...column,

      renderCell:

        column.field === 'status'

          ? (params: GridRenderCellParams) => (

              <StatusChip

                value={String(params.value)}

              />

            )

          : column.renderCell,

    })),

    {

      field: 'actions',

      headerName: 'Actions',

      width: 120,

      sortable: false,

      filterable: false,

      align: 'center',

      headerAlign: 'center',

      renderCell: (params) => (

        <>

          <IconButton

            color="primary"

            onClick={() =>

              onEdit?.(params.row)

            }

          >

            <EditIcon fontSize="small" />

          </IconButton>

          <IconButton

            color="error"

            onClick={() =>

              onDelete?.(params.row)

            }

          >

            <DeleteIcon fontSize="small" />

          </IconButton>

        </>

      ),

    },

  ]

  return (

    <Box

      sx={{

        height: 620,

        width: '100%',

      }}

    >

      <DataGrid

        rows={rows}

        columns={finalColumns}

        getRowId={(row) => row.uuid}

        pageSizeOptions={[10, 25, 50]}

        disableRowSelectionOnClick

        initialState={{

          pagination: {

            paginationModel: {

              pageSize: 10,

            },

          },

        }}

      />

    </Box>

  )

}