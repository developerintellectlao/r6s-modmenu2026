import * as React from 'react';
import Button from '@mui/material/Button';
import Dialog from '@mui/material/Dialog';
import DialogActions from '@mui/material/DialogActions';
import DialogContent from '@mui/material/DialogContent';
import DialogContentText from '@mui/material/DialogContentText';
import DialogTitle from '@mui/material/DialogTitle';
import Slide from '@mui/material/Slide';
import { Icon } from '@iconify/react';


const Transition = React.forwardRef(function Transition(props, ref) {
    return <Slide direction="left" ref={ref} {...props} />;
});

export default function AlertDialogSlide(props) {
    const { open, handleClose, size } = props;

    return (
        <React.Fragment>
            <Dialog
                open={open}
                // TransitionComponent={Transition}
                keepMounted
                onClose={handleClose}
                aria-describedby="alert-dialog-slide-description"
                fullWidth={true}
                
                maxWidth={size}
            >
                <DialogTitle sx={{ display: "flex", justifyContent: "flex-end" }}>
                    <Icon className='pointer_maker' onClick={handleClose} icon="system-uicons:cross" width="14" height="14" />
                </DialogTitle>

                <DialogContent>
                    <DialogContentText id="alert-dialog-slide-description" component="div">
                        {props.children}
                    </DialogContentText>
                </DialogContent>

            </Dialog>
        </React.Fragment>
    );
}