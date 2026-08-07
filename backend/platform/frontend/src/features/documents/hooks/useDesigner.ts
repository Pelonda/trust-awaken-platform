import { useDesignerStore }
from '../stores/designerStore'

export default function useDesigner() {

  return {

    add:
      useDesignerStore(
        state => state.add,
      ),

    update:
      useDesignerStore(
        state => state.update,
      ),

    remove:
      useDesignerStore(
        state => state.remove,
      ),

    select:
      useDesignerStore(
        state => state.select,
      ),

    objects:
      useDesignerStore(
        state => state.objects,
      ),

    selectedId:
      useDesignerStore(
        state => state.selectedId,
      ),

  }

}